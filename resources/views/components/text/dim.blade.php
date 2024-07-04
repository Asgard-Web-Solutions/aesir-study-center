@props(['label' => null])

<p class="my-1 text-sm text-neutral">@if ($label) {{ $label }} @endif {{ $slot }} </p>