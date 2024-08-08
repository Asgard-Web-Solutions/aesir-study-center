@extends('layouts.app2')

@section('content')

    <x-help.box>
        <x-help.text>Hi, I'm <x-help.highlight color="accent">Acolyte Quizalot</x-help.highlight>! I see that you met my friend, <x-help.highlight color="accent">Query the Help Owl</x-help.highlight>.</x-help.text>
        <x-help.text>We want to make sure you have the best time here at <x-help.highlight color="info">Acolyte Academy</x-help.highlight> as you use our resources to help you master your studies of magic, or whatever you are learning.</x-help.text>
        <x-help.text>If you see <x-help.highlight color="normal">Query</x-help.highlight> around anywhere, just click on her and she will summon me to give you information about a certain topic on the site.</x-help.text>
        <x-help.text>And if you don't like seeing <x-help.highlight color="normal">Query</x-help.highlight> follow you around to offer help, you can <x-help.highlight color="secondary">turn off tutorials in your Profile Settings</x-help.highlight>. Just click on your name at the top of this site to access them.</x-help.text>
        <x-help.text>Finally, if you need help with anything else, feel free to join our <x-page.communitylink>Community Forums</x-page.communitylink> where you can ask for help and interact with the <x-help.highlight color="info">Keeper</x-help.highlight>.</x-help.text>
        <x-help.text>Happy learning!</x-help.text>
    </x-help.box>
    
    <x-card.main title="Exams You Have Taken" size="grid">
        @forelse ($records as $record)
            <x-card.mini>
                <div class="block w-full md:flex">
                    <div class="w-full md:w-3/4">
                        <h2 class="my-2 text-xl"><a href="{{ route('exam.view', $record) }}" class="font-bold no-underline link link-primary">{{ $record->name }}</a></h2>
                    </div>
                    <div class="w-full md:w-1/4 tooltip" data-tip="{{ $mastery[$record->pivot->highest_mastery] }}">
                        <i class="
                            text-5xl
                            text-{{ config('color.' . strtolower($mastery[$record->pivot->highest_mastery])) }} 
                            {{ config('icon.' . strtolower($mastery[$record->pivot->highest_mastery])) }}
                            rounded-lg ring-2 p-1 ring-base-300
                        "></i>
                    </div>
                </div>
                
                <div class="flex w-full py-2 my-2 rounded-lg bg-base-100">
                    @if ($record->user) <a href="{{ route('profile.view', $record->user) }}"><x-user.avatar size="tiny">{{ $record->user->gravatarUrl(64) }}</x-user.avatar></a> <a href="{{ route('profile.view', $record->user) }}" class="mr-2 link link-{{ config('color.author') }} tooltip" data-tip="Exam Author">{{ $record->user->name }}</a> @endif
                    <span class="mx-2 tooltip text-{{ config('color.question_count') }}" data-tip="Question Count"><i class="mr-1 text-lg {{ config('icon.question_count') }}"></i> {{ $record->questions->count() }}</span>
                    <span class="mx-2 tooltip text-{{ config('color.times_taken') }}" data-tip="Times Taken"><i class="mr-1 text-lg {{ config('icon.times_taken') }}"></i> {{ $record->pivot->times_taken }}</span>
                    <span class="mx-2 tooltip text-{{ config('color.recent_average') }}" data-tip="Recent Average"><i class="mr-1 text-lg {{ config('icon.recent_average') }}"></i> {{ $record->pivot->recent_average }}</span>
                </div>

                @if ($record->questions->count())
                    <div class="flex w-full">
                        <div class="hidden text-sm sm:w-1/2 row @if ($record->pivot->mastery_mastered_count == 0) text-info-content @else text-{{ config('color.mastered') }} @endif sm:block">Mastery:</div><div class="w-full sm:w-1/2"><progress class="w-full lg:w-full progress progress-{{ config('color.mastered') }} " value="{{ $record->pivot->mastery_mastered_count / $record->questions->count() * 100 }}" max="100"></progress></div>
                    </div>
                    <div class="flex w-full">
                        <div class="hidden text-sm sm:w-1/2 row @if ($record->pivot->mastery_proficient_count == 0) text-info-content @else text-{{ config('color.proficient') }} @endif sm:block">Proficient:</div><div class="w-full sm:w-1/2"><progress class="w-full lg:w-full progress progress-{{ config('color.proficient') }} " value="{{ $record->pivot->mastery_proficient_count / $record->questions->count() * 100 }}" max="100"></progress></div>
                    </div>
                    <div class="flex w-full">
                        <div class="hidden text-sm sm:w-1/2 row @if ($record->pivot->mastery_familiar_count == 0) text-info-content @else text-{{ config('color.familiar') }} @endif sm:block">Familiar:</div><div class="w-full sm:w-1/2"><progress class="w-full lg:w-full progress progress-{{ config('color.familiar') }} " value="{{ $record->pivot->mastery_familiar_count / $record->questions->count() * 100 }}" max="100"></progress></div>
                    </div>
                    <div class="flex w-full">
                        <div class="hidden text-sm sm:w-1/2 row @if ($record->pivot->mastery_apprentice_count == 0) text-info-content @else text-{{ config('color.apprentice') }} @endif sm:block">Apprentice:</div><div class="w-full sm:w-1/2"><progress class="w-full lg:w-full progress progress-{{ config('color.apprentice') }} " value="{{ $record->pivot->mastery_apprentice_count / $record->questions->count() * 100 }}" max="100"></progress></div>
                    </div>
                    <br />
                @endif

                <div class="block w-full p-2 rounded-lg bg-base-100 md:flex">

                    <div class="w-full text-center md:w-1/2 md:text-left">
                        <div class="dropdown">
                            <div class="m-1 btn btn-secondary btn-sm btn-outline" tabindex="0" role="button">More Actions...</div>
                            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                                <li><a href="{{ route('exam-session.summary', $record) }}"><i class="{{ config('icon.latest_summary') }} text-lg"></i> Last Test Summary</a></li>
                                <li><a href="{{ route('practice.start', $record) }}"><i class="{{ config('icon.take_exam') }} text-lg"></i> Practice Flash Cards</a></li>
                                <li><a href="{{ route('exam-session.start', $record) }}"><i class="{{ config('icon.practice_exam') }} text-lg"></i> Take Exam</a></li>
                                @can('update', $record)
                                    <li><a href="{{ route('exam.edit', $record) }}"><i class="{{ config('icon.edit_exam') }} text-lg"></i> Edit Exam</a></li>
                                @endcan
                            </ul>
                        </div>
                    </div>

                    <div class="w-full text-center md:text-right md:w-1/2">
                        <a href="{{ route('exam-session.start', $record) }}" class="my-1 btn btn-primary"><i class="{{ config('icon.take_exam') }} text-xl"></i> Take Exam</a>
                    </div>
                </div>
            </x-card.mini>
        @empty
            <x-card.mini>
                <x-text.main>You have not taken a test yet. <a href="{{ route('exam.public') }}" class="link-primary link">Find a Public Exam</a> or else <a href="{{ route('exam.create') }}">Create Your Own Exams</a>!</x-text.main>
            </x-card.mini>
        @endforelse
    </x-card.main>

    <br />
    <div class="flex justify-end w-full space-x-2">
        <a href="{{ route('exam.index') }}" class="btn btn-primary"><i class="{{ config('icon.manage_exams') }} text-lg"></i> Manage Your Own Exams</a>
        <a href="{{ route('exam.public') }}" class="btn btn-secondary"><i class="{{ config('icon.public_exams') }} text-lg"></i> Find Public Exams</a>
    </div>
@endsection
