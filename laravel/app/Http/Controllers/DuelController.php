<?php

namespace App\Http\Controllers;

use App\DTOs\DuelDto;
use App\DTOs\DuelListDto;
use App\Models\Duel;
use App\Models\DuelVote;
use App\Models\Recipe;
use App\Models\User;
use App\Services\DuelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DuelController extends Controller
{
    private DuelService $duelService;

    /**
     * Constructor per injectar el servei de duels.
     */
    public function __construct(DuelService $duelService)
    {
        $this->duelService = $duelService;
    }

    /**
     * Llistar tots els duels amb paginació i filtres.
     */
    public function index(Request $request): View
    {
        $currentStatus = $request->input('status', 'tots');
        $query = $this->baseListQuery();

        if ($currentStatus !== 'tots') {
            $query->where('status', $currentStatus);
        }

        $duels = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
        $this->transformPaginatorCollection($duels);

        return view('duels.index', compact('duels', 'currentStatus'));
    }

    /**
     * Mostrar el formulari de creació d'un duel nou.
     */
    public function create(): View
    {
        $user = Auth::user();

        $myRecipes = $user->recipes()
            ->orderBy('title')
            ->get(['id', 'title']);

        $challengedUsers = User::query()
            ->whereKeyNot($user->id)
            ->whereHas('recipes')
            ->with([
                'recipes' => fn($query) => $query
                    ->select('id', 'user_id', 'title')
                    ->orderBy('title'),
            ])
            ->orderBy('name')
            ->get(['id', 'name', 'avatar']);

        $challengedRecipesByUser = $challengedUsers
            ->mapWithKeys(function (User $challengedUser) {
                return [
                    (string) $challengedUser->id => $challengedUser->recipes
                        ->map(fn(Recipe $recipe) => [
                            'id' => $recipe->id,
                            'title' => $recipe->title,
                        ])
                        ->values()
                        ->all(),
                ];
            });

        $defaultEndDate = now()->addDays(14)->toDateString();

        return view('duels.create', compact(
            'myRecipes',
            'challengedUsers',
            'challengedRecipesByUser',
            'defaultEndDate'
        ));
    }

    /**
     * Llistar els duels on participa l'usuari autenticat.
     */
    public function userDuels(Request $request): View
    {
        $userId = Auth::id();
        $activeTab = $request->input('tab') === 'received' ? 'received' : 'created';

        $createdDuels = $this->baseListQuery()
            ->where('challenger_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(8, ['*'], 'created_page')
            ->withQueryString();

        $receivedDuels = $this->baseListQuery()
            ->where('challenged_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(8, ['*'], 'received_page')
            ->withQueryString();

        $this->transformPaginatorCollection($createdDuels);
        $this->transformPaginatorCollection($receivedDuels);

        return view('duels.my-duels', compact('createdDuels', 'receivedDuels', 'activeTab'));
    }

    /**
     * Mostrar els detalls d'un duel específic.
     */
    public function show(Duel $duel): View
    {
        $duel->load([
            'challenger',
            'challenged',
            'challengerRecipe',
            'challengedRecipe',
            'winnerUser',
            'winnerRecipe',
            'topLevelComments.user',
            'topLevelComments.replies.user',
        ]);

        $duelDto = DuelDto::fromModel($duel);
        $userVotes = [];

        if (Auth::check()) {
            $userVotes = DuelVote::query()
                ->where('duel_id', $duel->id)
                ->where('user_id', Auth::id())
                ->pluck('rating', 'recipe_id')
                ->map(fn($rating) => (int) $rating)
                ->all();
        }

        return view('duels.show', compact('duelDto', 'userVotes'));
    }

    /**
     * Registrar un nou duel a la base de dades.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'challenger_recipe_id' => [
                'required',
                Rule::exists('recipes', 'id')->where(
                    fn($query) => $query->where('user_id', Auth::id())
                ),
            ],
            'challenged_id' => 'required|exists:users,id',
            'challenged_recipe_id' => 'required|exists:recipes,id',
            'end_date' => 'nullable|date|after:today',
        ]);

        if ((int) $data['challenged_id'] === (int) Auth::id()) {
            return back()
                ->withErrors(['challenged_id' => 'No et pots reptar a tu mateix.'])
                ->withInput();
        }

        $challengedRecipeBelongsToUser = Recipe::query()
            ->whereKey($data['challenged_recipe_id'])
            ->where('user_id', $data['challenged_id'])
            ->exists();

        if (!$challengedRecipeBelongsToUser) {
            return back()
                ->withErrors(['challenged_recipe_id' => 'La recepta seleccionada no pertany al rival indicat.'])
                ->withInput();
        }

        $duelAlreadyExists = Duel::query()
            ->where('challenger_recipe_id', $data['challenger_recipe_id'])
            ->where('challenged_recipe_id', $data['challenged_recipe_id'])
            ->exists();

        if ($duelAlreadyExists) {
            return back()
                ->withErrors(['challenged_recipe_id' => 'Aquestes dues receptes ja tenen un duel registrat.'])
                ->withInput();
        }

        try {
            $duel = $this->duelService->createDuel($data, Auth::user());
            return redirect()->route('duels.show', $duel)->with('success', 'Duel creat amb èxit!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Actualitzar l'estat d'un duel (només admin o participants per cancel·lació).
     */
    public function updateStatus(Request $request, Duel $duel)
    {
        $data = $request->validate([
            'status' => 'required|in:iniciat,finalitzat,peticio de cancelacio,cancelat'
        ]);

        // Lògica de permisos segons l'estat sol·licitat
        if ($data['status'] === 'peticio de cancelacio') {
            if (Auth::id() !== $duel->challenger_id && Auth::id() !== $duel->challenged_id) {
                abort(403, 'No autoritzat');
            }
        } else {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Només administradors');
            }
        }

        $duel->status = $data['status'];
        $duel->save();

        return back()->with('success', 'Estat actualitzat.');
    }

    /**
     * Registrar un vot per a una de les receptes del duel.
     */
    public function vote(Request $request, Duel $duel)
    {
        if ($duel->status !== 'iniciat' && $duel->status !== 'peticio de cancelacio') {
            return back()->withErrors(['error' => 'No es pot votar un duel tancat o cancel·lat.']);
        }

        $data = $request->validate([
            'recipe_id' => 'required|in:' . $duel->challenger_recipe_id . ',' . $duel->challenged_recipe_id,
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Cream o actualitzam el vot de l'usuari per a aquesta recepta en aquest duel
        DuelVote::updateOrCreate(
            ['duel_id' => $duel->id, 'user_id' => Auth::id(), 'recipe_id' => $data['recipe_id']],
            ['rating' => $data['rating']]
        );

        return back()->with('success', 'Vot guardat correctament.');
    }

    /**
     * Query base compartida pels llistats de duels.
     */
    private function baseListQuery()
    {
        return Duel::with([
            'challenger',
            'challenged',
            'challengerRecipe',
            'challengedRecipe',
            'winnerUser',
            'winnerRecipe',
        ]);
    }

    /**
     * Transformar la col·lecció paginada a DTOs de llistat.
     */
    private function transformPaginatorCollection($paginator): void
    {
        $paginator->getCollection()->transform(
            fn($duel) => DuelListDto::fromModel($duel)
        );
    }
}
