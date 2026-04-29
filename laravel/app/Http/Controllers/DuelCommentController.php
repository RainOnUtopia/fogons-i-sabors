<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DuelComment;
use App\Models\Duel;

/**
 * Controlador de comentaris per als duels.
 * 
 * Gestiona la creació, actualització i eliminació lògica de comentaris
 * associats als duels culinaris. Inclou suport per a peticions AJAX.
 * 
 * @package App\Http\Controllers
 */
class DuelCommentController extends Controller
{
    /**
     * Guarda un nou comentari per a un duel.
     * 
     * @param Request $request Petició amb el contingut del comentari i opcionalment el pare (per a respostes).
     * @param Duel $duel El duel al qual s'associa el comentari.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse Resposta JSON si es demana o redirecció.
     */
    public function store(Request $request, Duel $duel)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:duel_comments,id',
        ]);

        $comment = new DuelComment([
            'content' => $request->string('content'),
            'parent_id' => $request->input('parent_id'),
            'user_id' => $request->user()->id,
        ]);

        $duel->comments()->save($comment);

        if ($request->wantsJson() || $request->ajax()) {
            $comment->load('user');
            return response()->json([
                'success' => true,
                'comment' => $comment,
                'message' => __('comments.created')
            ]);
        }

        return back()->with('status', __('comments.created'));
    }

    /**
     * Actualitza el contingut d'un comentari.
     * 
     * @param Request $request Petició amb el nou contingut.
     * @param DuelComment $comment El comentari a actualitzar.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse Resposta JSON si es demana o redirecció.
     * @throws \Illuminate\Http\Exceptions\HttpResponseException Si l'usuari no té permís o el comentari està eliminat.
     */
    public function update(Request $request, DuelComment $comment)
    {
        if ($comment->is_deleted) {
            if ($request->wantsJson() || $request->ajax())
                return response()->json(['success' => false, 'message' => __('comments.cannot_edit_deleted')], 403);
            abort(403, __('comments.cannot_edit_deleted'));
        }

        if ($request->user()->id !== $comment->user_id && $request->user()->role !== 'admin') {
            if ($request->wantsJson() || $request->ajax())
                return response()->json(['success' => false, 'message' => __('comments.unauthorized_edit')], 403);
            abort(403, __('comments.unauthorized_edit'));
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->string('content'),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => $comment,
                'message' => __('comments.updated')
            ]);
        }

        return back()->with('status', __('comments.updated'));
    }

    /**
     * Marca un comentari com a eliminat.
     * 
     * No elimina el registre de la base de dades, sinó que canvia el contingut
     * per un missatge d'eliminació i marca el flag is_deleted.
     * 
     * @param Request $request Petició de l'usuari.
     * @param DuelComment $comment El comentari a eliminar.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse Resposta JSON si es demana o redirecció.
     */
    public function destroy(Request $request, DuelComment $comment)
    {
        $user = $request->user();

        if ($user->id !== $comment->user_id && $user->role !== 'admin') {
            if ($request->wantsJson() || $request->ajax())
                return response()->json(['success' => false, 'message' => __('comments.unauthorized_delete')], 403);
            abort(403, __('comments.unauthorized_delete'));
        }

        $isAdmin = ($user->id !== $comment->user_id && $user->role === 'admin');

        $text = $isAdmin
            ? __('comments.deleted_by_admin', ['default' => 'Comentari eliminat per un administrador'])
            : __('comments.deleted_by_user', ['default' => 'Comentari eliminat']);

        $comment->update([
            'content' => $text,
            'is_deleted' => true,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => $comment,
                'message' => __('comments.deleted')
            ]);
        }

        return back()->with('status', __('comments.deleted'));
    }
}
