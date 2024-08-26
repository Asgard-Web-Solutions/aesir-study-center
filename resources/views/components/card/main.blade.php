@props(['title' => null, 'size' => 'lg', 'hideDesktop' => false])

<div class="container px-4 py-4 mx-auto @if ($hideDesktop == true) lg:hidden @endif @if ($size == 'lg') w-full lg:w-3/4 xl:w-1/2 @elseif ($size == 'xl') w-full lg:3/4 @endif">
    <div class="p-6 rounded-lg shadow-lg bg-base-200 text-base-content">
        @if($title)<h2 class="mb-4 text-2xl font-semibold text-center">{{ $title }}</h2>@endif
        @if ($size == 'grid')
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                {{ $slot }}
            </div>
        @else
            {{ $slot }}
        @endif
    </div>
</div>