<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Trek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class MeetingController extends Controller
{
    // Listado de encuentros con filtro por excursión
    public function index(Request $request)
    {
        $trekId = $request->query('trek_id', 'all');
        $inscripcion = $request->query('inscripcion', 'all');
        $today = Carbon::today()->toDateString();

        $meetings = Meeting::query()
            ->with(['trek', 'user'])
            ->when($trekId !== 'all', function ($query) use ($trekId) {
                $query->where('trek_id', $trekId);
            })
            ->when($inscripcion !== 'all', function ($query) use ($inscripcion, $today) {
                if ($inscripcion === 'active') {
                    $query->whereDate('appDateEnd', '>=', $today);
                } elseif ($inscripcion === 'inactive') {
                    $query->whereDate('appDateEnd', '<', $today);
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
    public function create(Request $request)
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

        $day = Carbon::parse($data['day']);
        $appDateIni = $day->copy()->subMonthNoOverflow()->toDateString();
        $appDateEnd = $day->copy()->subWeek()->toDateString();

        $meeting = Meeting::create([
            'trek_id' => (int) $data['trek_id'],
            'user_id' => (int) $data['user_id'],
            'day' => $data['day'],
            'hour' => $data['hour'],
            'appDateIni' => $appDateIni,
            'appDateEnd' => $appDateEnd,
        ]);

        return redirect()
            ->route('admin.meetings.edit', $meeting)
            ->with('status', 'Encuentro creado.');
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

        return view('admin.meetings.edit', [
            'meeting' => $adminMeeting->load(['trek', 'user', 'users.role']),
            'treks' => $treks,
            'guides' => $guides,
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

        $day = Carbon::parse($data['day']);
        $appDateIni = $day->copy()->subMonthNoOverflow()->toDateString();
        $appDateEnd = $day->copy()->subWeek()->toDateString();

        $adminMeeting->update([
            'trek_id' => (int) $data['trek_id'],
            'user_id' => (int) $data['user_id'],
            'day' => $data['day'],
            'hour' => $data['hour'],
            'appDateIni' => $appDateIni,
            'appDateEnd' => $appDateEnd,
        ]);

        return redirect()
            ->route('admin.meetings.edit', $adminMeeting)
            ->with('status', 'Encuentro actualizado.');
    }

    // Elimina un encuentro
    public function destroy(Meeting $adminMeeting)
    {
        $adminMeeting->delete();

        return redirect()
            ->route('admin.meetings.index')
            ->with('status', 'Encuentro eliminado.');
    }

    // Añade un guía adicional
    public function addGuide(Request $request, Meeting $adminMeeting)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::query()->with('role')->findOrFail($data['user_id']);
        if ($user->role?->name !== 'guia') {
            return back()->withErrors(['user_id' => 'Solo se pueden añadir usuarios con rol guía.']);
        }

        $adminMeeting->users()->syncWithoutDetaching([$user->id]);

        return redirect()
            ->route('admin.meetings.edit', $adminMeeting)
            ->with('status', 'Guía añadido.');
    }

    // Quita un guía adicional
    public function removeGuide(Meeting $adminMeeting, User $user)
    {
        $adminMeeting->users()->detach($user->id);

        return redirect()
            ->route('admin.meetings.edit', $adminMeeting)
            ->with('status', 'Guía eliminado.');
    }
}
