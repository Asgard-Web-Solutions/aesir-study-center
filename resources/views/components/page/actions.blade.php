<div class="justify-end w-10/12 mx-auto my-5 text-right card-action">
    @if ($primary != "none")
        <a href="{{ $primaryLink }}" class="btn btn-primary">{{ __($primary) }}</a>
    @endif

    @if ($secondary != "none")
        <a href="{{ $secondaryLink }}" class="btn btn-secondary">{{ __($secondary) }}</a>
    @endif
</div>
