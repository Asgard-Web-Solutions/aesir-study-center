@props(['primaryLabel' => null, 'primaryAction' => '#', 'secondaryLabel' => null, 'secondaryAction' => '#', 'submitLabel' => null, 'alignButtons' => 'right'])

<div class="flex space-x-2 @if ($alignButtons == 'right') justify-end w-full @endif">
    @if ($submitLabel)
        <input type="submit" class="btn btn-primary" value="{!! $submitLabel !!}">
    @endif

    @if ($primaryLabel)
        <a href="{{ $primaryAction }}" class="btn btn-primary">{!! $primaryLabel !!}</a>
    @endif

    @if ($secondaryLabel)
        <a href="{{ $secondaryAction }}" class="btn btn-secondary">{!! $secondaryLabel !!}</a>
    @endif
</div>
