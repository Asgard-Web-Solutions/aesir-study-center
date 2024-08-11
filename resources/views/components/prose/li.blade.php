@props(['title' => ''])
<li class="py-1 ml-6 opacity-80"> @if ($title) <span class="font-bold text-primary">{!! $title !!}</span> @endif {!! $slot !!}</li>