<x-app-layout>
    <div class="py-6 bg-gradient-to-br from-sky-50 via-indigo-50 to-white">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-between items-center bg-white/90 border border-sky-100 shadow-sm sm:rounded-2xl px-5 py-4">
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-sky-700 bg-sky-100 rounded-full">Encuentro</span>
                    <h2 class="mt-2 font-semibold text-xl text-gray-800 leading-tight">Editar encuentro</h2>
                </div>
                <a href="{{ route('admin.meetings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                    Volver
                </a>
            </div>

            <div class="bg-white/90 border border-sky-100 shadow-sm sm:rounded-2xl">
                <div class="p-6 text-slate-900">
                    <form method="POST" action="{{ route('admin.meetings.update', $meeting->id) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        @include('admin.meetings.form', [
                            'meeting' => $meeting,
                        ])

                        <div class="flex items-center gap-3">
                            <x-primary-button type="submit">
                                Guardar cambios
                            </x-primary-button>
                            <a href="{{ route('admin.meetings.index') }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                                Volver a la lista
                            </a>
                        </div>
                    </form>

                    <div class="mt-10 border-t border-indigo-100 pt-6">
                        <div class="text-sm font-semibold text-slate-700 uppercase tracking-widest">Guías adicionales</div>
                        <form method="POST" action="{{ route('admin.meetings.guides.add', $meeting->id) }}" class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-end">
                            @csrf
                            <div class="w-full sm:flex-1">
                                <x-input-label for="guide_user_id" value="Añadir guía" />
                                <select id="guide_user_id" name="guide_user_id" class="mt-1 block w-full border-gray-300 focus:border-blue-600 focus:ring-blue-500 rounded-md shadow-sm">
                                    <option value="">Selecciona un guía</option>
                                    @foreach ($guides as $guide)
                                        <option value="{{ $guide->id }}" @selected((string) old('guide_user_id') === (string) $guide->id)>
                                            {{ $guide->lastname }} {{ $guide->name }} ({{ $guide->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->addGuide->get('guide_user_id')" class="mt-2" />
                            </div>
                            <x-primary-button type="submit">
                                Añadir
                            </x-primary-button>
                        </form>

                        <div class="mt-6">
                            @if ($extraGuides->isEmpty())
                                <div class="text-sm text-slate-500">No hay guías adicionales asignados.</div>
                            @else
                                <div class="divide-y divide-indigo-100 rounded-md border border-indigo-100 bg-indigo-50/30">
                                    @foreach ($extraGuides as $guide)
                                        <div class="flex items-center justify-between p-3">
                                            <div class="text-sm text-slate-800">
                                                {{ $guide->lastname }} {{ $guide->name }} ({{ $guide->email }})
                                            </div>
                                            <form method="POST" action="{{ route('admin.meetings.guides.remove', [$meeting->id, $guide->id]) }}" data-confirm="¿Quitar este guía?">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button type="submit">Quitar</x-danger-button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-10 border-t border-sky-100 pt-6">
                        <div class="text-sm font-semibold text-slate-700 uppercase tracking-widest">Inscripciones</div>
                        <div class="mt-4">
                            @if ($attendees->isEmpty())
                                <div class="text-sm text-slate-500">No hay inscripciones.</div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm">
                        <caption class="sr-only">Listado de asistentes inscritos</caption>
                                        <thead class="text-left text-sky-900 bg-sky-50 border-b border-sky-100">
                                            <tr>
                                                <th scope="col" class="py-2 pr-4">Nombre</th>
                                                <th scope="col" class="py-2 pr-4">Email</th>
                                                <th scope="col" class="py-2 pr-4">DNI</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($attendees as $attendee)
                                                <tr class="border-b border-sky-100">
                                                    <td class="py-2 pr-4">{{ $attendee->name }} {{ $attendee->lastname }}</td>
                                                    <td class="py-2 pr-4">{{ $attendee->email }}</td>
                                                    <td class="py-2 pr-4">{{ $attendee->dni }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
