<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl">
                <div class="p-6 text-slate-900">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <form method="GET" action="{{ route('admin.meetings.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end sm:flex-1">
                        <div class="w-full sm:w-72">
                            <x-input-label for="trek_id" :value="__('Excursión (código o nombre)')" />
                            <select id="trek_id" name="trek_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="all" @selected($trekId === 'all')>Todas</option>
                                @foreach ($treks as $trek)
                                    <option value="{{ $trek->id }}" @selected((string) $trekId === (string) $trek->id)>
                                        {{ $trek->regnumber }} - {{ $trek->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <x-primary-button type="submit">
                                {{ __('Filtrar') }}
                            </x-primary-button>
                            @if($trekId !== 'all')
                                <a href="{{ route('admin.meetings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                                    {{ __('Limpiar') }}
                                </a>
                            @endif
                        </div>
                        </form>

                        <div>
                            <a href="{{ route('admin.meetings.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-800">
                                {{ __('Nuevo encuentro') }}
                            </a>
                        </div>
                    </div>

                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-600 border-b">
                                <tr>
                                    <th class="py-2 pr-4">ID</th>
                                    <th class="py-2 pr-4">Excursión</th>
                                    <th class="py-2 pr-4">Guía</th>
                                    <th class="py-2 pr-4">Día</th>
                                    <th class="py-2 pr-4">Hora</th>
                                    <th class="py-2 pr-4">Inscripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($meetings as $meeting)
                                <tr class="border-b">
                                    <td class="py-2 pr-4">{{ $meeting->id }}</td>
                                    <td class="py-2 pr-4">{{ $meeting->trek?->name }}</td>
                                        <td class="py-2 pr-4">
                                            {{ $meeting->user?->name }} {{ $meeting->user?->lastname }}
                                        </td>
                                        <td class="py-2 pr-4">{{ $meeting->day }}</td>
                                        <td class="py-2 pr-4">{{ $meeting->hour }}</td>
                                    <td class="py-2 pr-4">
                                        {{ $meeting->appDateIni }} - {{ $meeting->appDateEnd }}
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-6 text-center text-gray-500">
                                            No hay encuentros para mostrar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-sm text-gray-500">
                        Mostrando {{ $meetings->firstItem() ?? 0 }} a {{ $meetings->lastItem() ?? 0 }} de {{ $meetings->total() }} resultados
                    </div>
                </div>
            </div>

            <div class="mt-2 flex justify-end">
                {{ $meetings->links('admin.pagination') }}
            </div>
        </div>
    </div>
</x-app-layout>
