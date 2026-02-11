@props(['name' => null, 'helptext' => null, 'value' => null, 'label' => null, 'values' => null, 'selected' => null, 'size' => 'lg'])

<div class="mb-4">
    <label for="{{ $name }}" class="block mb-1 text-sm font-medium text-primary">{{ $slot }}{{ $label }}</label>
    
    @if ($helptext) <x-text.dim>{{ $helptext }}</x-text.dim> @endif
       
    <select id="{{ $name }}" name="{{ $name }}" class="@if ($size == 'lg') w-full @elseif ($size == 'sm') w-full lg:w-1/4 @endif my-2 select select-bordered select  @error($name) select-error @else select-primary @enderror">
        @foreach ($values as $key=>$value)
            <option value="{{ $key }}" @if ($selected && $selected == $key) SELECTED @endif>{!! $value !!}</option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-1 text-xs text-error">{{ $message }}</p>
    @enderror
</div>
