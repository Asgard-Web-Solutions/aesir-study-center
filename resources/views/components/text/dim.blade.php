@props(['label' => null])

<p class="my-1 text-sm text-base-content-subtle">@if ($label) {{ $label }} @endif {{ $slot }} </p>