<x-app-layout>
    <div class="py-6 bg-gradient-to-br from-emerald-50 via-sky-50 to-white">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-between items-center bg-white/90 border border-emerald-100 shadow-sm sm:rounded-2xl px-5 py-4">
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-emerald-700 bg-emerald-100 rounded-full">Usuario</span>
                    <h2 class="mt-2 font-semibold text-xl text-gray-800 leading-tight">Editar usuario</h2>
                </div>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                    Volver
                </a>
            </div>

            <div class="bg-white/90 border border-emerald-100 shadow-sm sm:rounded-2xl">
                <div class="p-6 text-slate-900">
                    <x-flash-status class="mb-4" />
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="name" value="Nombre" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $user->name) }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="lastname" value="Apellidos" />
                            <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full" value="{{ old('lastname', $user->lastname) }}" required />
                            <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="dni" value="DNI" />
                            <x-text-input id="dni" name="dni" type="text" class="mt-1 block w-full" value="{{ old('dni', $user->dni) }}" required />
                            <x-input-error :messages="$errors->get('dni')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', $user->email) }}" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="phone" value="Teléfono" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" value="{{ old('phone', $user->phone) }}" required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="role_id" value="Rol" />
                            @if ($isAdminUser ?? false)
                                <x-text-input type="text" class="mt-1 block w-full bg-gray-100 text-gray-700" value="admin" disabled />
                                <input type="hidden" name="role_id" value="{{ $user->role_id }}">
                            @else
                                <select id="role_id" name="role_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="status" value="Estado" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="y" @selected(old('status', $user->status) === 'y')>Alta</option>
                                <option value="n" @selected(old('status', $user->status) === 'n')>Baja</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button type="submit">
                                Guardar
                            </x-primary-button>
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                                Volver a la lista
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
