@props(['title' => null])

<div>
    <div class="p-4 rounded-lg shadow-lg bg-neutral text-neutral-content card">
        @if ($title) <h3 class="mb-2 text-xl font-bold text-primary">{{ $title }}</h3> @endif
        
        {{ $slot }}
    </div>
</div>