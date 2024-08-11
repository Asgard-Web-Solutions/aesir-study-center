@props(['title' => ''])
<div class="py-1">
    @if ($title) <h3 class="pb-1 text-lg text-secondary">{!! $title !!}</h3> @endif
    {!! $slot !!}
</div>