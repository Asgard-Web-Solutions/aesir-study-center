@props(['hideMobile' => 'false', 'colspan' => 0])

<td class="@if ($hideMobile == 'true') hidden md:table-cell @endif p-2" @if ($colspan) colspan="{{ $colspan }}" @endif >{{ $slot }}</td>
