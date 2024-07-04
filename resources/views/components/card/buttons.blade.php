@props(['primaryLabel' => null, 'primaryAction' => '#', 'secondaryLabel' => null, 'secondaryAction' => '#', 'submitLabel' => null])

<div class="flex justify-end w-full space-x-2">
    @if ($submitLabel)
        <input type="submit" class="btn btn-primary" value="{{ $submitLabel }}">
    @endif

    @if ($primaryLabel)
        <a href="{{ $primaryAction }}" class="btn btn-primary">{{ $primaryLabel }}</a>
    @endif

    @if ($secondaryLabel)
        <a href="{{ $secondaryAction }}" class="btn btn-secondary">{{ $secondaryLabel }}</a>
    @endif
</div>
