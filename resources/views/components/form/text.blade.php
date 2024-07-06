@props(['type' => 'text', 'name' => null, 'helptext' => null, 'placeholder' => null, 'value' => null, 'label' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="block mb-1 text-sm font-medium text-primary">{{ $slot }}{{ $label }}</label>
    
    @if ($helptext) <x-text.dim>{{ $helptext }}</x-text.dim> @endif
    
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" class="w-full my-2 input @error($name) input-error @else input-primary @enderror" @if ($placeholder) placeholder="{{ $placeholder }}" @endif @if ($value) value="{{ $value }}" @endif>
    
    @error($name)
        <p class="mt-1 text-xs text-error">{{ $message }}</p>
    @enderror

</div>
