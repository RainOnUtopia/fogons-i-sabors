<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

/**
 * Controlador responsable de la gestió d'usuaris al panell d'administració.
 * 
 * Permet llistar els usuaris del sistema, cercar-los, filtrar per rol i estat,
 * així com editar el seu rol (usuari/administrador) i el seu estat.
 * 
 * @package App\Http\Controllers
 * @subpackage Admin
 */
class UserController extends Controller
{
    /**
     * Mostra la llista d'usuaris amb cerca, filtre i ordenació.
     * 
     * La lògica de construcció d'URLs d'ordenació es calcula aquí i es passa a la vista.
     * 
     * @param Request $request Petició amb els paràmetres de cerca i filtratge.
     * @return \Illuminate\View\View Vista amb la llista d'usuaris paginada.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Cerca per nom o correu electrònic
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function (\Illuminate\Database\Eloquent\Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtre per rol
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filtre per estat (is_active)
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Ordenació — columnes permeses per evitar injecció SQL
        $sortColumn = $request->input('sort', 'name');
        $sortDirection = $request->input('direction', 'asc');
        $allowedSortColumns = ['id', 'name', 'email', 'role', 'is_active'];

        if (in_array($sortColumn, $allowedSortColumns)) {
            $query->orderBy($sortColumn, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        $users = $query->paginate(15)->withQueryString();

        // Construcció d'URLs i indicadors d'ordenació per a cada columna — extret de la vista
        $sortData = [];
        foreach ($allowedSortColumns as $col) {
            $newDirection = ($sortColumn === $col && $sortDirection === 'asc') ? 'desc' : 'asc';
            $sortData[$col] = [
                'url' => $request->fullUrlWithQuery(['sort' => $col, 'direction' => $newDirection]),
                'indicator' => ($sortColumn === $col) ? ($sortDirection === 'asc' ? '↑' : '↓') : '',
            ];
        }

        return view('admin.users.index', compact('users', 'sortData'));
    }

    /**
     * Mostrar el formulari per editar el rol i l'estat de l'usuari.
     * 
     * @param User $user L'usuari a editar.
     * @return \Illuminate\View\View Vista del formulari d'edició.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Actualitza el rol i l'estat de l'usuari.
     * 
     * @param Request $request Petició amb les dades validades.
     * @param User $user L'usuari a actualitzar.
     * @return \Illuminate\Http\RedirectResponse Redirecció a la llista d'usuaris amb missatge d'èxit.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'string', 'in:user,admin'],
            'is_active' => ['required', 'boolean'],
        ]);

        $user->update([
            'role' => $validated['role'],
            'is_active' => $validated['is_active'],
        ]);

        return Redirect::route('admin.users.index')->with('status', 'user-updated');
    }
}
