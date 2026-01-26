<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Symfony\Component\HttpFoundation\Response;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): JsonResponse
    {
        // Validación de los datos recibidos en el registro
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:50', 'unique:' . User::class],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Obtenemos el ID del rol por defecto (visitant)
        $roleId = Role::where('name', 'visitant')->value('id');
        // Si no existe el rol, abortamos (error de configuración del sistema)
        abort_unless($roleId, 500, 'No hay ningún rol predeterminado configurado (visitant)');

        // Creamos el usuario normalizando algunos campos
        $user = User::create([
            'name' => mb_strtoupper($validated['name']),
            'lastname' => mb_strtoupper($validated['lastname']),
            'dni' => mb_strtoupper($validated['dni']),
            'email' => mb_strtolower($validated['email']),
            'phone' => $validated['phone'],
            'password' => $validated['password'],
            'role_id' => $roleId,
        ]);

        // Lanzamos el evento de registro
        event(new Registered($user));

        // Creamos un token de acceso para la API (Laravel Sanctum)
        $token = $user->createToken('api-token')->plainTextToken;

        // Respuesta JSON con el token y los datos básicos del usuario
        return response()->json([
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'dni' => $user->dni,
                'phone' => $user->phone,
            ],
        ], Response::HTTP_CREATED);
    }
}
