@props(['component'])

@foreach ([
    ['route' => 'admin.users.index', 'active' => 'admin.users.*', 'label' => __('Usuarios')],
    ['route' => 'admin.comments.index', 'active' => 'admin.comments.*', 'label' => __('Comentarios')],
    ['route' => 'admin.treks.index', 'active' => 'admin.treks.*', 'label' => __('Excursiones')],
    ['route' => 'admin.meetings.index', 'active' => 'admin.meetings.*', 'label' => __('Encuentros')],
    ['route' => 'admin.municipalities.index', 'active' => 'admin.municipalities.*', 'label' => __('Municipios')],
    ['route' => 'admin.places.index', 'active' => 'admin.places.*', 'label' => __('Lugares')],
] as $item)
    <x-dynamic-component :component="$component" :href="route($item['route'])" :active="request()->routeIs($item['active'])">
        {{ $item['label'] }}
    </x-dynamic-component>
@endforeach
