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

    public function __construct(DuelService $duelService)
    {
        $this->duelService = $duelService;
    }

    public function index(Request $request)
    {
        $query = Duel::with(['challenger', 'challenged', 'challengerRecipe', 'challengedRecipe', 'winnerUser', 'winnerRecipe']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $duels = $query->orderBy('created_at', 'desc')->paginate(10);

        $duels->getCollection()->transform(function ($duel) {
            return DuelListDto::fromModel($duel);
        });

        return view('duels.index', compact('duels'));
    }

    public function userDuels()
    {
        $userId = Auth::id();

        $duels = Duel::with(['challenger', 'challenged', 'challengerRecipe', 'challengedRecipe', 'winnerUser', 'winnerRecipe'])
            ->where(function ($query) use ($userId) {
                $query->where('challenger_id', $userId)
                    ->orWhere('challenged_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $duels->getCollection()->transform(function ($duel) {
            return DuelListDto::fromModel($duel);
        });

        return view('duels.index', compact('duels'));
    }

    public function show(Duel $duel)
    {
        $duel->load(['challenger', 'challenged', 'challengerRecipe', 'challengedRecipe', 'winnerUser', 'winnerRecipe']);
        $duelDto = DuelDto::fromModel($duel);

        return view('duels.show', compact('duelDto', 'duel'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'challenger_recipe_id' => 'required|exists:recipes,id',
            'challenged_id' => 'required|exists:users,id',
            'challenged_recipe_id' => 'required|exists:recipes,id',
            'end_date' => 'nullable|date|after:today'
        ]);

        try {
            $duel = $this->duelService->createDuel($data, Auth::user());
            return redirect()->route('duels.show', $duel)->with('success', 'Duel creat amb èxit!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request, Duel $duel)
    {
        $data = $request->validate([
            'status' => 'required|in:iniciat,finalitzat,peticio de cancelacio,cancelat'
        ]);

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

    public function vote(Request $request, Duel $duel)
    {
        if ($duel->status !== 'iniciat' && $duel->status !== 'peticio de cancelacio') {
            return back()->withErrors(['error' => 'No es pot votar un duel tancat o cancel·lat.']);
        }

        $data = $request->validate([
            'recipe_id' => 'required|in:' . $duel->challenger_recipe_id . ',' . $duel->challenged_recipe_id,
            'rating' => 'required|integer|min:1|max:5',
        ]);

        DuelVote::updateOrCreate(
            ['duel_id' => $duel->id, 'user_id' => Auth::id(), 'recipe_id' => $data['recipe_id']],
            ['rating' => $data['rating']]
        );

        return back()->with('success', 'Vot guardat correctament.');
    }
}
