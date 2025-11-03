@php
    $state = $getState();
    if (is_string($state)) {
        $data = json_decode($state, true);
    } elseif (is_array($state)) {
        $data = $state;
    } else {
        $data = null;
    }
@endphp

@if (is_array($data))
    <div class="text-xs font-mono space-y-1">
        @foreach($data as $key => $value)
            <div class="capitalize py-2">
                <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $key }}:</span>
                <span class="text-gray-700 dark:text-gray-200">
                    @if(is_array($value))
                        {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}
                    @elseif(is_bool($value))
                        {{ $value ? 'True' : 'False' }}
                    @else
                        {{ ucfirst($value) }}
                    @endif
                </span>
            </div>
        @endforeach
    </div>
@else
    <span class="text-gray-400 italic">—</span>
@endif
