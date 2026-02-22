<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Listado con filtros y búsqueda de usuarios
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q'));
        $roles = Role::query()->orderBy('name')->get();
        $role = (string) $request->query('role', 'all');
        $status = (string) $request->query('status', 'all');

        $allowedRoles = $roles->pluck('name')->all();
        if (! in_array($role, [...$allowedRoles, 'all'], true)) {
            $role = 'all';
        }

        if (! in_array($status, ['all', 'alta', 'baja'], true)) {
            $status = 'all';
        }

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
        $roles = Role::query()
            ->whereIn('name', ['guia', 'visitant'])
            ->orderBy('name')
            ->get();

        $adminRoleId = Role::query()
            ->where('name', 'admin')
            ->value('id');

        return view('admin.users.edit', [
            'user' => $adminUser->load('role'),
            'roles' => $roles,
            'isAdminUser' => $adminRoleId !== null && (int) $adminUser->role_id === (int) $adminRoleId,
        ]);
    }

    // Actualización de datos básicos, rol y estado
    public function update(Request $request, User $adminUser)
    {
        $editableRoleIds = Role::query()
            ->whereIn('name', ['guia', 'visitant'])
            ->pluck('id')
            ->all();

        $adminRoleId = Role::query()
            ->where('name', 'admin')
            ->value('id');

        $allowedRoleIds = $adminRoleId !== null && (int) $adminUser->role_id === (int) $adminRoleId
            ? [$adminRoleId]
            : $editableRoleIds;

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
            'role_id' => ['required', Rule::in($allowedRoleIds)],
            'status' => ['required', Rule::in(['y', 'n'])],
        ]);

        if ($adminRoleId !== null
            && (int) $adminUser->role_id === (int) $adminRoleId
            && $data['status'] === 'n') {
            return back()
                ->withErrors([
                    'status' => 'No se puede dar de baja a un administrador.',
                ])
                ->withInput();
        }

        $data['name'] = mb_strtoupper($data['name']);
        $data['lastname'] = mb_strtoupper($data['lastname']);
        $data['dni'] = mb_strtoupper($data['dni']);
        $data['email'] = mb_strtolower($data['email']);

        $adminUser->update($data);

        return redirect()
            ->route('admin.users.edit', $adminUser->id)
            ->with('status', 'Usuario actualizado');
    }

    // Da de baja rápida desde el listado
    public function deactivate(User $adminUser)
    {
        if ($adminUser->role?->name === 'admin') {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'No se puede dar de baja a un administrador');
        }

        try {
            $adminUser->update([
                'status' => 'n',
            ]);
        } catch (QueryException $e) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'No se pudo dar de baja al usuario');
        }

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuario dado de baja');
    }

    // Da de alta rápida desde el listado
    public function activate(User $adminUser)
    {
        if ($adminUser->role?->name === 'admin') {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'No se puede modificar el estado de un administrador');
        }

        try {
            $adminUser->update([
                'status' => 'y',
            ]);
        } catch (QueryException $e) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'No se pudo dar de alta al usuario');
        }

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuario dado de alta');
    }
}
