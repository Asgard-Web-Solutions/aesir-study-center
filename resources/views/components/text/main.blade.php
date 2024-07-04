@props(['label'])

<p class="mb-4 text-gray-200">@if ($label){{ $label }}@endif {{ $slot }}</p>