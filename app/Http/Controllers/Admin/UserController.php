<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Listado con filtros y búsqueda de usuarios
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q'));
        $role = $request->query('role', 'all');
        $status = $request->query('status', 'all');
        $roles = Role::query()->orderBy('name')->get();

        $users = User::query()
            ->with('role')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('dni', 'like', "%{$search}%");
                });
            })
            ->when($role !== 'all', function ($query) use ($role) {
                $query->whereHas('role', function ($roleQuery) use ($role) {
                    $roleQuery->where('name', $role);
                });
            })
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('status', $status === 'alta' ? 'y' : 'n');
            })
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => $roles,
            'search' => $search,
            'role' => $role,
            'status' => $status,
        ]);
    }

    // Vista de detalle de usuario
    public function show(User $adminUser)
    {
        $user = $adminUser->load([
            'role',
            'comments.meeting.trek',
            'meetings.trek',
        ]);

        $createdMeetings = Meeting::query()
            ->with('trek')
            ->where('user_id', $user->id)
            ->orderByDesc('day')
            ->orderByDesc('hour')
            ->get();

        return view('admin.users.show', [
            'user' => $user,
            'createdMeetings' => $createdMeetings,
        ]);
    }

    // Formulario de edición de usuario
    public function edit(User $adminUser)
    {
        $roles = Role::query()->orderBy('name')->get();

        return view('admin.users.edit', [
            'user' => $adminUser->load('role'),
            'roles' => $roles,
        ]);
    }

    // Actualización de datos básicos, rol y estado
    public function update(Request $request, User $adminUser)
    {
        $roles = Role::query()->orderBy('name')->pluck('id')->all();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'dni' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'dni')->ignore($adminUser->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($adminUser->id),
            ],
            'phone' => ['required', 'string', 'max:255'],
            'role_id' => ['required', Rule::in($roles)],
            'status' => ['required', Rule::in(['y', 'n'])],
        ]);

        $data['name'] = mb_strtoupper($data['name']);
        $data['lastname'] = mb_strtoupper($data['lastname']);
        $data['dni'] = mb_strtoupper($data['dni']);
        $data['email'] = mb_strtolower($data['email']);

        $adminUser->update($data);

        return redirect()
            ->route('admin.users.edit', $adminUser->id)
            ->with('status', 'Usuario actualizado.');
    }

    // Da de baja rápida desde el listado
    public function deactivate(User $adminUser)
    {
        $adminUser->update([
            'status' => 'n',
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuario dado de baja.');
    }

    // Da de alta rápida desde el listado
    public function activate(User $adminUser)
    {
        $adminUser->update([
            'status' => 'y',
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuario dado de alta.');
    }
}
