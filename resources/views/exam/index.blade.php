@extends('layouts.app2', ['heading' => 'Manage Authored Exams'])

@section('content')

    <x-card.main title="Author Exams" size="lg">
        <x-help.box>
            <x-help.text>The <x-help.highlight color="warning">Authors</x-help.highlight> are the life blood of the community here at <x-help.highlight color="info">Acolyte Academy</x-help.highlight>. They are the ones that create exams for other acolytes like yourself to take.</x-help.text>
            <x-help.text>If you have an idea for a great exam, please create it and then set it to <x-help.highlight color="{{ config('color.public') }}">Public</x-help.highlight> so other acolytes can take it! There just might be some hidden rewards in it for you if your exams become popular.</x-help.text>
            <x-help.text>The more high quality exams that you create will help you become known to the other acolytes at the academy. They will even be displayed in your <x-help.highlight color="secondary">transcripts</x-help.highlight>.</x-help.text>
        </x-help.box>

        @forelse ($exams as $exam)
            @can ('view', $exam)
                <x-card.mini>
                    <div class="block w-full md:flex">
                        <div class="w-full md:w-3/4">
                            <h2 class="my-2 text-xl"><a href="{{ route('exam.view', $exam) }}" class="font-bold no-underline link link-primary">{{ $exam->name }}</a></h2>
                        </div>
                    </div>
                    
                    <div class="flex w-full py-2 my-2 rounded-lg bg-base-100">
                        @if ($exam->user) <a href="{{ route('profile.view', $exam->user) }}"><x-user.avatar size="tiny">{{ $exam->user->gravatarUrl(64) }}</x-user.avatar></a> <a href="{{ route('profile.view', $exam->user) }}" class="mr-2 link link-{{ config('color.author') }} tooltip" data-tip="Architect">{{ $exam->user->name }}</a> @endif
                        <span class="mx-2 tooltip text-{{ config('color.question_count') }}" data-tip="Question Count"><i class="mr-1 text-lg {{ config('icon.question_count') }}"></i> {{ $exam->questions->count() }}</span>
                        <span class="mx-2 tooltip text-{{ config('color.acolyte_count') }}" data-tip="Acolytes"><i class="mr-1 text-lg {{ config('icon.acolyte_count') }}"></i> {{ $exam->records->count() }}</span>
                        @if ($exam->visibility) <span class="mx-2 tooltip text-{{ config('color.public') }}" data-tip="Public Exam"><i class="mr-1 text-lg {{ config('icon.public') }}"></i> Public</span> @endif
                        @if (!$exam->visibility) <span class="mx-2 tooltip text-{{ config('color.private') }}" data-tip="Private Exam"><i class="mr-1 text-lg {{ config('icon.private') }}"></i> Private</span> @endif
                    </div>

                    <div class="w-full text-right rounded-md bg-base-100">
                        <div class="dropdown">
                            <div class="m-1 btn btn-secondary btn-sm btn-outline" tabindex="0" role="button">More Actions...</div>
                            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                                <li><a href="{{ route('exam-session.summary', $exam) }}"><i class="{{ config('icon.latest_summary') }} text-lg"></i> Last Test Summary</a></li>
                                <li><a href="{{ route('practice.start', $exam) }}"><i class="{{ config('icon.take_exam') }} text-lg"></i> Practice Flash Cards</a></li>
                                <li><a href="{{ route('exam-session.start', $exam) }}"><i class="{{ config('icon.practice_exam') }} text-lg"></i> Take Exam</a></li>
                                @can('update', $exam)
                                    <li><a href="{{ route('exam.edit', $exam) }}"><i class="{{ config('icon.edit_exam') }} text-lg"></i> Edit Exam</a></li>
                                @endcan
                            </ul>
                        </div>
                    </div>
        
                </x-card.mini>
            @endcan
        @empty
            <x-card.mini>
                <x-text.main>No Exams Created.</x-text.main>
            </x-card.mini>
        @endforelse
    </x-card.main>

    <x-card.main>
        <div class="w-full text-center">
            @feature('mage-upgradae')
                @if (auth()->user()->credit->architect >= 1)
                    <a href="{{ route('exam.create') }}" class="btn btn-primary"><i class="text-lg {{ config('icon.new_exam') }}"></i> Create an Exam</a>
                @else
                    <a href="{{ route('exam.create') }}" class="btn btn-primary btn-disabled" @disabled(true)><i class="text-lg {{ config('icon.new_exam') }}"></i> Not enough Architect Credits to create an Exam</a>
                    <x-help.box>
                        <x-help.text>Oh no! What happened to the <x-help.highlight color="warning">Create an Exam</x-help.highlight> button?</x-help.text>
                        <x-help.text>Acolytes who are <x-help.text color="accent">Adepts</x-help.text>, that is, are using a <x-help.highlight color="accent">Free Account</x-help.highlight>, can only create a certain number of exams. Once your <x-help.text color="secondary">Architect Credits</x-help.text> are used up, you will have to obtain more credits, or <x-help.text color="accent">Upgrade to Mage</x-help.text> level to create more exams.</x-help.text>
                        <x-help.text>To obtain more credits you can <x-help.text color="secondary">Master an Exam</x-help.text>, either yours or another public exam. You will also get an <x-help.text color="secondary">Architect Credit</x-help.text> if other people master your exams, so make sure to make it a good one!</x-help.text>
                        <x-help.text>Upgrading to Mage helps to support Acolyte Academy, allowing it to continue running and receiving updates, so if you get benefit from this site, please consider doing so.</x-help.text>
                    </x-help.box>
                @endif
            @else
                <a href="{{ route('exam.create') }}" class="btn btn-primary"><i class="text-lg {{ config('icon.new_exam') }}"></i> Create an Exam</a>
            @endfeature
        </div>
    </x-card.main>
@endsection
