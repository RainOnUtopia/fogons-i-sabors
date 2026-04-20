<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DuelComment;
use App\Models\Duel;

class DuelCommentController extends Controller
{
    /**
     * Guarda un nou comentari per a un duel.
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
