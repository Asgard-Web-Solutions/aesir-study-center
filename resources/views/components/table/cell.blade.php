@props(['hideMobile' => 'false'])

<td class="@if ($hideMobile == 'true') hidden md:table-cell @endif p-2">{{ $slot }}</td>
