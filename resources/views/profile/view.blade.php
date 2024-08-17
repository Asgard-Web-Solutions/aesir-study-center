@extends('layouts.app2')

@section('content')
    <x-page.title>Acolyte Transcript</x-page.title>
    
    <x-card.main size="lg" title="Personal Info">
        <x-card.mini >
    
            <div class="block md:flex">
                <div>
                    <x-user.avatar size='lg'>{{ $user->gravatarUrl(256) }}</x-user.avatar>
                </div>
                <div class="block ml-4 text-justify">
                    <span class="text-2xl font-bold text-primary">{{ $user->name }}</span>

                </div>
            </div>

            @if ($user->id == auth()->user()->id)
                <x-help.box>
                    <x-help.text>Welcome to your <x-help.highlight>Acolyte Transcripts</x-help.highlight>!</x-help.text>
                    <x-help.text>This is your official <x-help.highlight color="accent">public</x-help.highlight> record for everything that you do here at <x-help.highlight color="info">Acolyte Acadmey</x-help.highlight>. All acolytes can see this information and can track your progress in your Exams.</x-help.text>
                    <x-help.text>But don't worry, any exams that are marked as <x-help.highlight color="accent">private</x-help.highlight> are only visible to you. None of the other acolytes will be able to see that information on your transcript.</x-help.text>
                    <x-help.text>Now go make progress so you can show off all of those <x-help.highlight color="secondary">Mastery Badgets</x-help.highlight> that you are working so hard to earn!</x-help.text>
                </x-help.box>
            @endif
        </x-card.mini>
    </x-card.main>

    @feature('mage-upgrade')
        {{-- Hide this, only admins and the user can see this --}}
        <x-card.main title="Subscription Information">
            <x-card.mini>
                Show subscription info here...
            </x-card.mini>

            @if (auth()->user()->isAdmin)
                <div class="collapse">
                    <input type="checkbox">
                    <div class="text-center collapse-title"><div class="btn btn-outline btn-secondary">Gift Subscriptions</div></div>
                    <div class="collapse-content">
                        <x-card.mini title="Gift Mage Membership">
            
                        </x-card.mini>
                    </div>
                </div>
            @endif

            @can ('view', $user->credit)
                <x-card.mini title="Mage Credits">
                    <div class="shadow stats stats-vertical lg:stats-horizontal">
                        <div class="stat">
                            <div class="stat-title">Architect Credits</div>
                            <div class="stat-value">{{ $user->credit->architect }}</div>
                            <div class="stat-desc"># of Exams you can Create</div>
                        </div>

                        <div class="stat">
                            <div class="stat-title">Study Credits</div>
                            <div class="stat-value">{{ $user->credit->study }}</div>
                            <div class="stat-desc"># of Public Exams you can Take</div>
                        </div>
                    </div>
                </x-card.mini>
            @endcan

        </x-card.main>
    @endfeature

    <x-card.main title="Exam History">
        
        @forelse ($user->records as $record)
            @can ('view', $record)
                <x-card.mini>
                    <div class="block w-full md:flex">
                        <div class="w-full md:w-3/4">
                            <h2 class="my-2 text-xl"><a href="{{ route('exam.view', $record) }}" class="font-bold no-underline link link-primary">{{ $record->name }}</a></h2>
                        </div>
                        <div class="w-full md:w-1/4 tooltip" data-tip="Mastery Level: {{ $mastery[$record->pivot->highest_mastery] }}">
                            <i class="
                                text-5xl
                                text-{{ config('color.' . strtolower($mastery[$record->pivot->highest_mastery])) }} 
                                {{ config('icon.' . strtolower($mastery[$record->pivot->highest_mastery])) }}
                                rounded-lg ring-2 p-1 ring-base-300
                            "></i>
                        </div>
                    </div>
                    
                    <div class="flex w-full py-2 my-2 rounded-lg bg-base-100">
                        @if ($record->user) <a href="{{ route('profile.view', $record->user) }}"><x-user.avatar size="tiny">{{ $record->user->gravatarUrl(64) }}</x-user.avatar></a> <a href="{{ route('profile.view', $record->user) }}" class="mr-2 link link-{{ config('color.author') }} tooltip" data-tip="Architect">{{ $record->user->name }}</a> @endif
                        <span class="mx-2 tooltip text-{{ config('color.question_count') }}" data-tip="Question Count"><i class="mr-1 text-lg {{ config('icon.question_count') }}"></i> {{ $record->questions->count() }}</span>
                        <span class="mx-2 tooltip text-{{ config('color.times_taken') }}" data-tip="Times Taken"><i class="mr-1 text-lg {{ config('icon.times_taken') }}"></i> {{ $record->pivot->times_taken }}</span>
                        <span class="mx-2 tooltip text-{{ config('color.recent_average') }}" data-tip="Recent Average"><i class="mr-1 text-lg {{ config('icon.recent_average') }}"></i> {{ $record->pivot->recent_average }}</span>
                        @if ($record->visibility) <span class="mx-2 tooltip text-{{ config('color.public') }}" data-tip="Public Exam"><i class="mr-1 text-lg {{ config('icon.public') }}"></i> Public</span> @endif
                        @if (!$record->visibility) <span class="mx-2 tooltip text-{{ config('color.private') }}" data-tip="Private Exam"><i class="mr-1 text-lg {{ config('icon.private') }}"></i> Private</span> @endif
                    </div>
                </x-card.mini>
            @endcan
        @empty
            <x-card.mini>
                <x-text.main>Exam history was not found.</x-text.main>
            </x-card.mini>
        @endforelse
    </x-card.main>

    <x-card.main title="Exams Architected">
        <x-help.box>
            <x-help.text>I know, <x-help.highlight>Architected</x-help.highlight> is such a weird word!</x-help.text>
            <x-help.text>The <x-help.highlight color="none">architects</x-help.highlight> are the life blood of the community here at <x-help.highlight color="info">Acolyte Academy</x-help.highlight>. They are the ones that create exams for other acolytes like yourself to take.</x-help.text>
            <x-help.text>If you have an idea for a great exam, please create it and then set it to <x-help.highlight color="{{ config('color.public') }}">Public</x-help.highlight> so other acolytes can take it! There just might be some hidden rewards in it for you if your exams become popular.</x-help.text>
            <x-help.text>The more high quality exams that you create will help you become known to the other acolytes at the academy. They will even be displayed in your <x-help.highlight color="secondary">transcripts</x-help.highlight>.</x-help.text>
        </x-help.box>

        @forelse ($user->exams as $exam)
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

                    <div class="w-full text-right">
                        @can ('update', $exam) 
                            <a href="{{ route('exam.edit', $exam) }}" class="mx-2 btn btn-sm"><i class="{{ config('icon.edit_exam') }} text-lg"></i> Edit Exam</a> 
                        @endcan
                        @can ('view', $exam) 
                            <a href="{{ route('exam-session.start', $exam) }}" class="mx-2 btn btn-primary btn-sm"><i class="{{ config('icon.practice_exam') }} text-lg"></i> Take Exam</a> 
                        @endcan
                    </div>
        
                </x-card.mini>
            @endcan
        @empty
            <x-card.mini>
                <x-text.main>No Exams Created.</x-text.main>
            </x-card.mini>
        @endforelse
    </x-card.main>
@endsection
