@props(['name' => null, 'helptext' => null, 'value' => null, 'label' => null, 'size' => 'full', 'rows' => 2])

@php
    $text = "";

    if ($value) {
        $text = old($name, $value);
    } else {
        $text = old($name);
    }
@endphp

<div class="@if ($size == 'full') w-full @elseif ($size == 'half') w-full md:w-1/2 md:px-1 @endif mb-4">
    <label for="{{ $name }}" class="block mb-1 text-sm font-medium text-primary">{{ $slot }}{{ $label }}</label>

    @if ($helptext) <x-text.dim>{{ $helptext }}</x-text.dim> @endif

    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" class="resize h-full w-full my-2 input @error($name) input-error @else input-primary @enderror">{!! $text !!}</textarea>

    @error($name)
        <p class="mt-1 text-xs text-error">{{ $message }}</p>
    @enderror
</div>
