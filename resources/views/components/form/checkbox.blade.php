@props(['name' => null, 'helptext' => null, 'label' => null, 'value' => '1', 'size' => 'full', 'checked' => false, 'style' => 'check'])

<div class="@if ($size == 'full') w-full @elseif ($size == 'half') w-full md:w-1/2 md:px-1 @endif mb-4">
    <label class="inline-flex items-center cursor-pointer label">
        <input type="checkbox" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}" class="
            @if ($style == 'check') checkbox checkbox-primary @endif 
            @if ($style == 'toggle') toggle toggle-primary @endif
            @error($name) border-error @else border-primary @enderror
        " @if ($checked) checked @endif />
        <span class="ml-2 text-sm font-medium text-primary">{{ $slot }}{{ $label }}</span>
    </label>
    
    @if ($helptext) <x-text.dim>{{ $helptext }}</x-text.dim> @endif
    
    @error($name)
        <p class="mt-1 text-xs text-error">{{ $message }}</p>
    @enderror
</div>
