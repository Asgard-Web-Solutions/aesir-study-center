<div class="m-auto my-10 shadow-xl sm:w-full md:w-10/12 card bg-neutral text-neutral-content">
    
    <div class="w-full card-body">
        <x-card.header :text="$header" />

        <div class=" text-neutral-content">
            {{ $slot }}
        </div>
    </div>
</div>