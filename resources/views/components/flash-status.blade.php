@props([
    'status' => session('status'),
    'error' => session('error'),
])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-sm text-green-700 bg-green-50 border border-green-200 rounded p-3']) }}>
        {{ $status }}
    </div>
@elseif ($error)
    <div {{ $attributes->merge(['class' => 'text-sm text-red-700 bg-red-50 border border-red-200 rounded p-3']) }}>
        {{ $error }}
    </div>
@endif
