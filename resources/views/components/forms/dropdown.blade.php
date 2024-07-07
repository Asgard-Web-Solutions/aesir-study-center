<div class="w-full mx-4 my-4 form-control">
    <label class="label" for="input-{{ $name }}">
        <span class="label-text text-neutral-content">{{ __($label) }}</span>
    </label>

    <select id="input-{{ $name }}" name="{{ $name }}" class="max-w-lg select select-primary text-primary-content">
        @foreach ($values as $key=>$value)
            <option value="{{ $key }}" @if ($selected && $selected == $key) SELECTED @endif>{{ $value }}</option>
        @endforeach
    </select>
</div>

@error($name)
    <div class="mx-4 my-4 shadow-lg alert alert-error">
        <div>
            <i class="fa-regular fa-circle-xmark"></i>
            <span>{{ $message }}</span>
        </div>
    </div>
@enderror
