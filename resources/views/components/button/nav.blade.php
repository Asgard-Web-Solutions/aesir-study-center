@props(['href' => null, 'color' => 'ghost'])

<a href="{{ $href }}" class="btn btn-{{ $color }}">{!! $slot !!}</a>