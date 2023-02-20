<div class="w-full mx-4 my-4 form-control">
    <label class="label" for="input-{{ $name }}">
        <span class="label-text text-neutral-content">{{ __($label) }}</span>
    </label>
    <input id="input-{{ $name }}" name="{{ $name }}" type="text" value="{{ $value }}" class="max-w-lg input input-bordered input-primary text-primary-content">
</div>

@error($name)
    <div class="mx-4 my-4 shadow-lg alert alert-error">
        <div>
            <i class="fa-regular fa-circle-xmark"></i>
            <span>{{ $message }}</span>
        </div>
    </div>
@enderror
