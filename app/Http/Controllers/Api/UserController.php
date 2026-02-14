<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserDestroyRequest;
use App\Http\Requests\UserPasswordUpdateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Listado de usuarios (uso administrativo).
     * Devuelve todos los usuarios junto con su rol.
     */
    public function index()
    {
        // Cargamos los usuarios con su rol
        $users = User::with('role')->orderBy('id')->get();

        // Lo devuelvemos como colección de recursos
        return UserResource::collection($users);
    }

    /**
     * Mostrar un usuario concreto con toda su información relacionada.
     */
    public function show(User $user): UserResource
    {
        // Cargamos relaciones necesarias
        $user->load([
            'meeting.comments.images',
            'meetings' => function ($query) use ($user) {
                $query->with([
                    'trek',
                    'user',
                    'comments' => function ($commentQuery) use ($user) {
                        $commentQuery
                            ->where('user_id', $user->id)
                            ->with(['user', 'images']);
                    }
                ]);
            },
            'comments.images' // Comentarios hechos por el usuario
        ]);

        return new UserResource($user);
    }

    /**
     * Actualizar un usuario.
     * - El admin puede actualizar a cualquiera.
     * - Un usuario normal solo puede actualizarse a sí mismo.
     */
    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        // Seguridad adicional: comprobamos permisos a nivel de controlador
        if (
            ! $request->user()->isAdmin() &&
            $request->user()->id !== $user->id
        ) {
            abort(403, 'No puedes actualizar a otros usuarios.');
        }

        // Datos ya validados por el Request
        $data = $request->validated();

        if (array_key_exists('name', $data)) {
            $data['name'] = mb_strtoupper($data['name']);
        }

        if (array_key_exists('lastname', $data)) {
            $data['lastname'] = mb_strtoupper($data['lastname']);
        }

        if (array_key_exists('dni', $data)) {
            $data['dni'] = mb_strtoupper($data['dni']);
        }

        if (array_key_exists('email', $data)) {
            $data['email'] = mb_strtolower($data['email']);
        }

        // Actualizamos el usuario
        $user->update($data);

        // Devolvemos el usuario actualizado con el rol recargado
        return new UserResource($user->refresh()->load('role'));
    }

    /**
     * Eliminar un usuario.
     * - Admin puede borrar a cualquiera.
     * - Usuario normal solo puede borrarse a sí mismo (controlado en UserDestroyRequest).
     */
    public function destroy(UserDestroyRequest $request, User $user): JsonResponse
    {
        // Usamos una transacción para asegurar consistencia
        DB::transaction(function () use ($user) {

            $user->tokens()->delete();
            $user->update(['status' => 'n']);
        });

        return response()->json(['message' => 'Usuario desactivado!']);
    }

    /**
     * Actualizar la contraseña del usuario autenticado.
     */
    public function updatePassword(UserPasswordUpdateRequest $request): JsonResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return response()->json([
            'message' => 'Contraseña actualizada correctamente.',
        ]);
    }
}
