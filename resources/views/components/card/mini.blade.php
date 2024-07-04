@props(['title' => null])

<div>
    <div class="p-4 text-white bg-gray-900 rounded-lg shadow-lg card">
        @if ($title) <h3 class="mb-2 text-xl font-bold">{{ $title }}</h3> @endif
        
        {{ $slot }}
    </div>
</div>