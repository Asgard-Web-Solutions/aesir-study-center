@props(['size' => 'sm'])

<div class="mx-2 avatar">
    <div class="@if ($size == 'sm') w-10 rounded-md ring-offset-4 ring-1 @elseif ($size == 'lg') w-24 rounded-xl ring-offset-2 ring-3 @elseif ($size == 'tiny') w-6 rounded-full ring-offset-1 ring-1 @endif ring ring-primary ring-offset-base-100">
        <img src="{{ $slot }}" />
    </div>
</div>
