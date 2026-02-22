<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Trek;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    // Listado de comentarios con filtros por estado
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $trekId = $request->query('trek_id', 'all');

        $comments = Comment::query()
            ->with(['user', 'meeting.trek'])
            ->withCount('images')
            ->when($trekId !== 'all', function ($query) use ($trekId) {
                $query->whereHas('meeting', function ($meetingQuery) use ($trekId) {
                    $meetingQuery->where('trek_id', $trekId);
                });
            })
            ->when($status === 'approved', function ($query) {
                $query->where('status', 'y');
            })
            ->when($status === 'pending', function ($query) {
                $query->where('status', '!=', 'y');
            })
            ->when($status === 'all', function ($query) {
                $query->orderByRaw("CASE WHEN status = 'y' THEN 1 ELSE 0 END ASC");
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $treks = Trek::query()
            ->orderBy('regnumber')
            ->get();

        return view('admin.comments.index', [
            'comments' => $comments,
            'status' => $status,
            'trekId' => $trekId,
            'treks' => $treks,
        ]);
    }

    // Vista de detalle del comentario
    public function show(Comment $adminComment)
    {
        $comment = $adminComment->load(['user.role', 'meeting.trek', 'meeting.user', 'images']);

        return view('admin.comments.show', [
            'comment' => $comment,
        ]);
    }

    // Vista de detalle para moderación del comentario
    public function edit(Comment $adminComment)
    {
        $adminComment->load(['user', 'meeting.trek', 'images']);

        return view('admin.comments.edit', [
            'comment' => $adminComment,
        ]);
    }

    // Actualiza el estado del comentario (aprobado/pendiente)
    public function update(Request $request, Comment $adminComment)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['y', 'n'])],
        ]);

        try {
            $adminComment->update($data);
        } catch (QueryException $e) {
            return redirect()
                ->route('admin.comments.edit', $adminComment)
                ->with('error', 'No se pudo actualizar el estado del comentario');
        }

        return redirect()
            ->route('admin.comments.edit', $adminComment)
            ->with('status', 'Comentario actualizado');
    }
}
