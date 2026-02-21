<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Trek;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    // Listado de encuentros con filtro por excursión
    public function index(Request $request)
    {
        $trekId = $request->query('trek_id', 'all');
        $inscripcion = $request->query('inscripcion', 'all');
        $enrollmentCloseBoundary = Carbon::today()->addWeek()->toDateString();

        $meetings = Meeting::query()
            ->with(['trek', 'user'])
            ->when($trekId !== 'all', function ($query) use ($trekId) {
                $query->where('trek_id', $trekId);
            })
            ->when($inscripcion !== 'all', function ($query) use ($inscripcion, $enrollmentCloseBoundary) {
                if ($inscripcion === 'active') {
                    $query->whereDate('day', '>=', $enrollmentCloseBoundary);
                } elseif ($inscripcion === 'inactive') {
                    $query->whereDate('day', '<', $enrollmentCloseBoundary);
                }
            })
            ->orderByDesc('day')
            ->orderByDesc('hour')
            ->paginate(20)
            ->withQueryString();

        $treks = Trek::query()
            ->orderBy('regnumber')
            ->get();

        return view('admin.meetings.index', [
            'meetings' => $meetings,
            'treks' => $treks,
            'trekId' => $trekId,
            'inscripcion' => $inscripcion,
        ]);
    }

    // Formulario de creación de encuentro
    public function create()
    {
        $treks = Trek::query()
            ->orderBy('regnumber')
            ->get();

        $guides = User::query()
            ->whereHas('role', function ($query) {
                $query->where('roles.name', 'guia');
            })
            ->orderBy('lastname')
            ->orderBy('name')
            ->get();

        return view('admin.meetings.create', [
            'meeting' => new Meeting(),
            'treks' => $treks,
            'guides' => $guides,
        ]);
    }

    // Guarda un encuentro nuevo
    public function store(Request $request)
    {
        $data = $request->validate([
            'trek_id' => ['required', 'exists:treks,id'],
            'user_id' => ['required', 'exists:users,id'],
            'day' => ['required', 'date'],
            'hour' => ['required', 'date_format:H:i'],
        ]);

        $guide = User::query()->with('role')->findOrFail($data['user_id']);
        if ($guide->role?->name !== 'guia') {
            return back()->withErrors(['user_id' => 'El guía principal debe tener rol guía.'])->withInput();
        }

        $enrollmentDates = Meeting::enrollmentDatesForDay($data['day']);

        $meeting = Meeting::create([
            'trek_id' => (int) $data['trek_id'],
            'user_id' => (int) $data['user_id'],
            'day' => $data['day'],
            'hour' => $data['hour'],
            'appDateIni' => $enrollmentDates['appDateIni'],
            'appDateEnd' => $enrollmentDates['appDateEnd'],
        ]);

        return redirect()
            ->route('admin.meetings.edit', $meeting)
            ->with('status', 'Encuentro creado');
    }

    // Vista de detalle de encuentro
    public function show(Meeting $adminMeeting)
    {
        $meeting = $adminMeeting->load([
            'trek.municipality.island',
            'user.role',
            'users.role',
            'comments' => fn ($query) => $query
                ->with(['user', 'images'])
                ->withCount('images')
                ->latest(),
        ]);

        $extraGuides = $meeting->users->where('role.name', 'guia')->values();
        $attendees = $meeting->users->where('role.name', '!=', 'guia')->values();

        return view('admin.meetings.show', [
            'meeting' => $meeting,
            'extraGuides' => $extraGuides,
            'attendees' => $attendees,
        ]);
    }

    // Formulario de edición de encuentro
    public function edit(Meeting $adminMeeting)
    {
        $treks = Trek::query()
            ->orderBy('regnumber')
            ->get();

        $guides = User::query()
            ->whereHas('role', function ($query) {
                $query->where('roles.name', 'guia');
            })
            ->orderBy('lastname')
            ->orderBy('name')
            ->get();

        $meeting = $adminMeeting->load(['trek', 'user', 'users.role']);
        $extraGuides = $meeting->users->where('role.name', 'guia')->values();
        $attendees = $meeting->users->where('role.name', '!=', 'guia')->values();

        return view('admin.meetings.edit', [
            'meeting' => $meeting,
            'treks' => $treks,
            'guides' => $guides,
            'extraGuides' => $extraGuides,
            'attendees' => $attendees,
        ]);
    }

    // Actualiza un encuentro existente
    public function update(Request $request, Meeting $adminMeeting)
    {
        $data = $request->validate([
            'trek_id' => ['required', 'exists:treks,id'],
            'user_id' => ['required', 'exists:users,id'],
            'day' => ['required', 'date'],
            'hour' => ['required', 'date_format:H:i'],
        ]);

        $guide = User::query()->with('role')->findOrFail($data['user_id']);
        if ($guide->role?->name !== 'guia') {
            return back()->withErrors(['user_id' => 'El guía principal debe tener rol guía.'])->withInput();
        }

        $enrollmentDates = Meeting::enrollmentDatesForDay($data['day']);

        $adminMeeting->update([
            'trek_id' => (int) $data['trek_id'],
            'user_id' => (int) $data['user_id'],
            'day' => $data['day'],
            'hour' => $data['hour'],
            'appDateIni' => $enrollmentDates['appDateIni'],
            'appDateEnd' => $enrollmentDates['appDateEnd'],
        ]);

        return redirect()
            ->route('admin.meetings.edit', $adminMeeting)
            ->with('status', 'Encuentro actualizado');
    }

    // Elimina un encuentro
    public function destroy(Meeting $adminMeeting)
    {
        try {
            $adminMeeting->delete();
        } catch (QueryException $e) {
            return redirect()
                ->route('admin.meetings.index')
                ->with('error', 'No se puede eliminar el encuentro porque tiene datos relacionados');
        }

        return redirect()
            ->route('admin.meetings.index')
            ->with('status', 'Encuentro eliminado');
    }

    // Añade un guía adicional
    public function addGuide(Request $request, Meeting $adminMeeting)
    {
        $data = $request->validateWithBag('addGuide', [
            'guide_user_id' => ['required', 'exists:users,id'],
        ]);

        $guide = User::query()
            ->with('role')
            ->findOrFail((int) $data['guide_user_id']);

        if ($guide->role?->name !== 'guia') {
            return redirect()
                ->route('admin.meetings.edit', $adminMeeting)
                ->withErrors([
                    'guide_user_id' => 'Solo puedes añadir usuarios con rol guía.',
                ], 'addGuide');
        }

        if ((int) $guide->id === (int) $adminMeeting->user_id) {
            return redirect()
                ->route('admin.meetings.edit', $adminMeeting)
                ->withErrors([
                    'guide_user_id' => 'El guía principal ya está asignado en este encuentro.',
                ], 'addGuide');
        }

        $alreadyAttached = $adminMeeting->users()
            ->where('users.id', $guide->id)
            ->exists();

        if ($alreadyAttached) {
            return redirect()
                ->route('admin.meetings.edit', $adminMeeting)
                ->withErrors([
                    'guide_user_id' => 'Ese guía ya está asignado como guía adicional.',
                ], 'addGuide');
        }

        $adminMeeting->users()->syncWithoutDetaching([$guide->id]);

        return redirect()
            ->route('admin.meetings.edit', $adminMeeting)
            ->with('status', 'Guía añadido');
    }

    // Quita un guía adicional
    public function removeGuide(Meeting $adminMeeting, User $user)
    {
        if ((int) $user->id === (int) $adminMeeting->user_id) {
            return redirect()
                ->route('admin.meetings.edit', $adminMeeting)
                ->with('error', 'No se puede quitar el guía principal del encuentro.');
        }

        if ($user->role?->name !== 'guia') {
            return redirect()
                ->route('admin.meetings.edit', $adminMeeting)
                ->with('error', 'Solo se pueden quitar guías adicionales desde esta acción.');
        }

        $isAttached = $adminMeeting->users()
            ->where('users.id', $user->id)
            ->exists();

        if (! $isAttached) {
            return redirect()
                ->route('admin.meetings.edit', $adminMeeting)
                ->with('error', 'El usuario no está asignado como guía adicional en este encuentro.');
        }

        $adminMeeting->users()->detach($user->id);

        return redirect()
            ->route('admin.meetings.edit', $adminMeeting)
            ->with('status', 'Guía eliminado');
    }
}
