@props(['label' => null])

<p class="mb-4 text-gray-200">@if ($label){{ $label }}@endif {{ $slot }}</p>