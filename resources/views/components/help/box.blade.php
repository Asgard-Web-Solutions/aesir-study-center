@props(['open' => ''])

@auth
    @if (auth()->user()->showTutorial)
        <div class="collapse @if ($open) collapse-open @endif">
            <input type="checkbox" />
            <div class="collapse-title">
                <div class="flex text-right avatar tooltip tooltip-right" data-tip="Query the Help Owl">
                    <img src="{{ asset('/images/QueryTheOwl.png') }}" style="height: 35px; width: 25px;" />
                    <span class="m-2 text-xs font-bold text-neutral-content">Query the Help Owl</span>
                </div>
            </div>
            <div class="block p-2 md:flex rounded-t-xl collapse-content bg-base-100 text-neutral-content">
                <div class="w-full text-center md:w-1/6">
                    <img src="{{ asset('/images/AcolyteQuizalot.png') }}" class="mx-auto" style="height: 125px;;" />
                    <span class="text-xs font-bold text-info">Acolyte Quizalot</span>
                </div>
                <div class="w-full md:w-5/6">
                    {!! $slot !!}
                </div>
            </div>
        </div>
    @endif
@endauth