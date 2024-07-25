@props(['size' => 'sm'])

<div class="avatar">
    <div class="@if ($size == 'sm') w-12 rounded-md @elseif ($size == 'lg') w-24 rounded-xl @endif ring ring-offset-2 ring-primary ring-offset-base-100 ">
        <img src="{{ $slot }}" />
    </div>
</div>
