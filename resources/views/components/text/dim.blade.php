@props(['label' => null])

<p class="my-1 text-sm text-base-content-subtle">@if ($label) <span class="text-info">{{ $label }}</span> @endif {{ $slot }} </p>