<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TrekController;
use App\Http\Controllers\Api\MeetingSubscriptionController;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserDestroyRequest;
use App\Http\Requests\UserPasswordUpdateRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Models\Meeting;
use App\Models\Trek;
use Illuminate\Support\Facades\Auth;

// ROUTE MODEL BINDING PERSONALIZADO "USERS"

    // Permitimos resolver {user} tanto por ID como por email
    Route::bind('user', function ($value) {
        $user = is_numeric($value)
            ? User::find($value)
            : User::where('email', $value)->first();

        // Si no existe el usuario, devolvemos 404
        abort_if(! $user, 404, 'El usuario no existe!');

        return $user;
    });


// ROUTE MODEL BINDING PERSONALIZADO "TREKS"

Route::bind('trek', function ($value) {
    $needle = is_string($value) ? trim($value) : $value;

    $normalizedNeedle = is_string($needle) ? mb_strtoupper($needle) : $needle;

    $trek = is_numeric($needle)
        ? Trek::find($needle)
        : Trek::where('regnumber', $normalizedNeedle)->first();

    abort_if(! $trek, 404, 'La ruta no existe!');

    return $trek;
});

// RUTAS PÚBLICAS

// Registro de nuevos usuarios
Route::post('/register', [RegisteredUserController::class, 'store']);
// Login de usuario (devuelve token Sanctum, sin sesión)
Route::post('/login', function (LoginRequest $request) {
    $request->authenticate();

    $user = Auth::user();
    $token = $user->createToken('api')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => new UserResource($user->load('role')),
    ]);
});
// Exploración de rutas
Route::get('/treks', [TrekController::class, 'index']);
Route::get('/treks/{trek}', [TrekController::class, 'show']);


// RUTAS PROTEGIDAS (auth:sanctum u API KEY)
Route::middleware('auth.or.api.key')->group(function () {

    // Devuelvemos el usuario autenticado transformado mediante UserResource, 
    // que se encarga de definir la estructura y los datos expuestos en la API
    Route::get('/user', function (Request $request) {
        $user = $request->user();

        $meetings = Meeting::query()
            ->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->orWhereHas('comments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with([
                'trek',
                'user',
                'comments' => function ($commentQuery) use ($user) {
                    $commentQuery
                        ->where('user_id', $user->id)
                        ->with(['images']);
                }
            ])
            ->get();

        $user->setRelation('meetings', $meetings);

        return new UserResource($user);
    });

    // Actualizamos el perfil propio del usuario
    Route::put('/user', function (UserUpdateRequest $request) {
        // Reutilizamos el método update() del UserController
        return app(UserController::class)
            ->update($request, $request->user());
    });

    // Eliminamos la cuenta del usuario autenticado
    Route::delete('/user', function (UserDestroyRequest $request) {
        // Reutilizamos el método destroy() del UserController
        return app(UserController::class)
            ->destroy($request, $request->user());
    });

    // Actualizamos la contraseña del usuario autenticado
    Route::put('/user/password', function (UserPasswordUpdateRequest $request) {
        return app(UserController::class)->updatePassword($request);
    });


    // AUTENTICACIÓN

    // Logout del usuario (revoca el token actual)
    Route::post('/logout', function (Request $request) {
        $user = $request->user();
        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'Logout OK']);
    });


    // RUTAS DE ADMIN

    // Solo usuarios con rol admin
    Route::middleware('check.role.admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::post('/treks', [TrekController::class, 'store']);
    });

    // Eliminar usuarios:
    // - Admin puede eliminar a cualquiera
    // - Usuario normal solo puede eliminarse a sí mismo (controlado por UserDestroyRequest::authorize())
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    // Suscripción a meetings para el usuario autenticado
    Route::post('/meetings/{meeting}/subscribe', [MeetingSubscriptionController::class, 'store']);
    Route::delete('/meetings/{meeting}/subscribe', [MeetingSubscriptionController::class, 'destroy']);
});
