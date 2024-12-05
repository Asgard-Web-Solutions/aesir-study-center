@props(['size' => 'sm'])

<div class="mx-2 avatar">
    <div class="@if ($size == 'sm') w-10 rounded-full ring-offset-4 ring-1 @elseif ($size == 'lg') w-24 rounded-full ring-offset-2 ring-3 @elseif ($size == 'md') w-16 rounded-full ring-offset-2 ring-3 @elseif ($size == 'tiny') w-6 rounded-full ring-offset-1 ring-1 @endif ring ring-primary ring-offset-base-100">
        <img src="{{ $slot }}" />
    </div>
</div>
