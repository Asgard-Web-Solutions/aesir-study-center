@props(['hideMobile' => 'false'])

<th class="@if ($hideMobile == 'true') hidden md:table-cell @endif p-2">{{ $slot }}</th>
