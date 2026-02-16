<x-app-layout>
    <div class="py-6 bg-gradient-to-br from-sky-50 via-cyan-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 border border-sky-100 shadow-sm sm:rounded-2xl">
                <div class="p-6 text-slate-900">
                    <x-flash-status class="mb-4" />

                    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end sm:flex-1">
                        <div class="w-full sm:flex-1">
                            <x-input-label for="q" value="Buscar" />
                            <x-text-input id="q" name="q" type="text" class="mt-1 block w-full" value="{{ $search }}" placeholder="Nombre, email o DNI" />
                        </div>
                        <div class="w-full sm:max-w-xs">
                            <x-input-label for="role" value="Rol" />
                            <select id="role" name="role" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="all" @selected($role === 'all')>Todos</option>
                                @foreach ($roles as $roleOption)
                                    <option value="{{ $roleOption->name }}" @selected($role === $roleOption->name)>
                                        {{ $roleOption->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full sm:max-w-xs">
                            <x-input-label for="status" value="Estado" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="all" @selected($status === 'all')>Todos</option>
                                <option value="alta" @selected($status === 'alta')>Alta</option>
                                <option value="baja" @selected($status === 'baja')>Baja</option>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <x-primary-button type="submit">
                                Buscar
                            </x-primary-button>
                            @if($search !== '' || $role !== 'all' || $status !== 'all')
                                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                                    Limpiar
                                </a>
                            @endif
                        </div>
                    </form>

                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-sky-900 bg-sky-50 border-b border-sky-100">
                                <tr>
                                    <th class="py-2 pr-4">ID</th>
                                    <th class="py-2 pr-4">Nombre</th>
                                    <th class="py-2 pr-4">Email</th>
                                    <th class="py-2 pr-4">DNI</th>
                                    <th class="py-2 pr-4">Rol</th>
                                    <th class="py-2 pr-4">Estado</th>
                                    <th class="py-2 pr-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    @php
                                        $isAdminUser = $user->role?->name === 'admin';
                                    @endphp
                                    <tr class="border-b">
                                        <td class="py-2 pr-4">{{ $user->id }}</td>
                                        <td class="py-2 pr-4">
                                            {{ $user->name }} {{ $user->lastname }}
                                        </td>
                                        <td class="py-2 pr-4">{{ $user->email }}</td>
                                        <td class="py-2 pr-4">{{ $user->dni }}</td>
                                        <td class="py-2 pr-4">
                                            @if ($user->role?->name === 'admin')
                                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-purple-800 bg-purple-100 rounded-full">
                                                    admin
                                                </span>
                                            @elseif ($user->role?->name === 'guia')
                                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-amber-800 bg-amber-100 rounded-full">
                                                    guia
                                                </span>
                                            @elseif ($user->role?->name === 'visitant')
                                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-orange-800 bg-orange-100 rounded-full">
                                                    visitant
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-slate-700 bg-slate-100 rounded-full">
                                                    {{ $user->role?->name ?? '-' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-2 pr-4">
                                            @if ($user->status !== 'n')
                                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-green-700 bg-green-100 rounded-full">
                                                    Alta
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-red-700 bg-red-100 rounded-full">
                                                    Baja
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-2 pr-4 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                @if ($isAdminUser)
                                                    <button type="button" disabled class="inline-flex items-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white bg-gray-400 rounded-md cursor-not-allowed opacity-70">
                                                        Ver
                                                    </button>
                                                @else
                                                    <a href="{{ route('admin.users.show', $user->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white bg-green-700 rounded-md hover:bg-green-600">
                                                        Ver
                                                    </a>
                                                @endif
                                                @if ($isAdminUser)
                                                    <button type="button" disabled class="inline-flex items-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white bg-gray-400 rounded-md cursor-not-allowed opacity-70">
                                                        Editar
                                                    </button>
                                                @else
                                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white bg-blue-900 rounded-md hover:bg-blue-800">
                                                        Editar
                                                    </a>
                                                @endif
                                            @if ($user->status !== 'n')
                                                @if ($isAdminUser)
                                                    <button type="button" disabled class="inline-flex items-center justify-center w-32 px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white bg-gray-400 rounded-md cursor-not-allowed opacity-70">
                                                        Dar de baja
                                                    </button>
                                                @else
                                                    <form method="POST" action="{{ route('admin.users.deactivate', $user->id) }}" onsubmit="return confirm('¿Seguro que quieres dar de baja este usuario?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="inline-flex items-center justify-center w-32 px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white bg-red-700 rounded-md hover:bg-red-600">
                                                            Dar de baja
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <form method="POST" action="{{ route('admin.users.activate', $user->id) }}" onsubmit="return confirm('¿Seguro que quieres dar de alta este usuario?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center justify-center w-32 px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white bg-green-500 rounded-md hover:bg-green-400">
                                                        Dar de alta
                                                    </button>
                                                </form>
                                            @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-500">
                                            No hay usuarios para mostrar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-sm text-gray-500">
                        Mostrando {{ $users->firstItem() ?? 0 }} a {{ $users->lastItem() ?? 0 }} de {{ $users->total() }} resultados
                    </div>
                </div>
            </div>

            <div class="mt-2 flex justify-end">
                {{ $users->links('admin.pagination') }}
            </div>
        </div>
    </div>
</x-app-layout>
