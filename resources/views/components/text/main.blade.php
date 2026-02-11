@props(['label' => null])

<p class="mb-4 text-sm text-gray-200">@if ($label)<span class="text-secondary">{{ $label }}</span> @endif {{ $slot }}</p>