@props(['status' => session('status')])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-sm text-green-700 bg-green-50 border border-green-200 rounded p-3']) }}>
        {{ $status }}
    </div>
@endif
