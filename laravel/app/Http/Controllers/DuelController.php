<?php

namespace App\Http\Controllers;

use App\Models\Duel;
use App\Models\DuelVote;
use App\Services\DuelService;
use App\DTOs\DuelListDto;
use App\DTOs\DuelDto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index(Request $request)
    {
        // Carregam relacions per evitar el problema N+1
        $query = Duel::with(['challenger', 'challenged', 'challengerRecipe', 'challengedRecipe', 'winnerUser', 'winnerRecipe']);

        // Aplicam filtre per estat si existeix
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Paginació de 10 elements per pàgina
        $duels = $query->orderBy('created_at', 'desc')->paginate(10);

        // Transformam els models en DTOs per a la vista
        $duels->getCollection()->transform(function ($duel) {
            return DuelListDto::fromModel($duel);
        });

        return view('duels.index', compact('duels'));
    }

    /**
     * Llistar els duels on participa l'usuari autenticat.
     */
    public function userDuels()
    {
        $userId = Auth::id();

        // Cerquem duels on l'usuari sigui el reptador o el reptat
        $duels = Duel::with(['challenger', 'challenged', 'challengerRecipe', 'challengedRecipe', 'winnerUser', 'winnerRecipe'])
            ->where(function ($query) use ($userId) {
                $query->where('challenger_id', $userId)
                    ->orWhere('challenged_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Transformam els models en DTOs per a la vista
        $duels->getCollection()->transform(function ($duel) {
            return DuelListDto::fromModel($duel);
        });

        return view('duels.index', compact('duels'));
    }

    /**
     * Mostrar els detalls d'un duel específic.
     */
    public function show(Duel $duel)
    {
        // Càrrega ansiosa de relacions, incloent comentaris i respostes
        $duel->load([
            'challenger', 
            'challenged', 
            'challengerRecipe', 
            'challengedRecipe', 
            'winnerUser', 
            'winnerRecipe',
            'topLevelComments.user',
            'topLevelComments.replies.user'
        ]);
        
        $duelDto = DuelDto::fromModel($duel);

        return view('duels.show', compact('duelDto'));
    }

    /**
     * Registrar un nou duel a la base de dades.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'challenger_recipe_id' => 'required|exists:recipes,id',
            'challenged_id' => 'required|exists:users,id',
            'challenged_recipe_id' => 'required|exists:recipes,id',
            'end_date' => 'nullable|date|after:today'
        ]);

        try {
            // Utilitzam el servei de duels per a la creació
            $duel = $this->duelService->createDuel($data, Auth::user());
            return redirect()->route('duels.show', $duel)->with('success', 'Duel creat amb èxit!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
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
        // Verificam que el duel estigui actiu
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
}
