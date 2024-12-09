<div>
    <flux:modal.trigger name="view-insights">
        <flux:button class="btn btn-secondary" >Instructor Insights</flux:button>
    </flux:modal.trigger>

    <flux:modal name="view-insights" variant="flyout" class="w-full space-y-6 md:w-1/2">
        <div>
            <flux:heading size="lg">Question Mastery Insights</flux:heading>
            <flux:subheading>Explore this question and answer more in-depth.</flux:subheading>
        </div>

        <x-help.box>
            <x-help.text><x-help.highlight color="info">Acolyte Academy</x-help.highlight> has several instructors that are able to help you learn the material required to master your exams.</x-help.text>
            <x-help.text><x-help.highlight color="accent">Research Wizard Oddity</x-help.highlight> is a playful instructor that likes to use analogies and metaphors to help teach the concepts required in the question you are struggling with.</x-help.text>
            <x-help.text><x-help.highlight color="accent">Professor Bamboo</x-help.highlight> is more straight forward in his instructions. He doesn't use as many anologies in favor of just explaining things plainly.</x-help.text>
            <x-help.text>The Exam Author may have also provided their own insights for this question that may be of help for you.</x-help.text>
            <x-help.text>Choose whichever instructor helps you understand the content better! If you have any suggestions to improve the communication with the instructors make sure to <x-page.communitylink>share that with us on the Forums</x-page.communitylink>!</x-help.text>
        </x-help.box>

        <flux:tab.group>
            <flux:tabs>
                @foreach ($personalities as $personality)
                    @php
                        $disabled = false;

                        if ($personality['id'] == 0 && !$insights[0]) {
                            $disabled = true;
                        }
                    @endphp

                    @if ($disabled)
                        <flux:tooltip content="Exam Author has not recorded Insights for this question."><span>
                            <flux:tab name="{{ $personality['name'] }}" disabled>
                                <x-user.avatar size="tiny">{{ $personality['avatarUrl'] }}</x-user.avatar> {{ $personality['name'] }}
                            </flux:tab>
                        </span></flux:tooltip>
                    @else
                        <flux:tab name="{{ $personality['name'] }}"><x-user.avatar size="tiny">{{ $personality['avatarUrl'] }}</x-user.avatar> {{ $personality['name'] }}</flux:tab>
                    @endif
                @endforeach
            </flux:tabs>

            @foreach ($personalities as $personality)
                <flux:tab.panel name="{{ $personality['name'] }}">

                    <div class="flex my-6">
                        <x-user.avatar size='md'>{{ $personality['avatarUrl'] }}</x-user.avatar>
                        <span class="mx-2">&nbsp;</span>
                        {{ $personality['title'] }} {{ $personality['name'] }} @if ($personality['species'] != 'human') the {{ $personality['species'] }} @endif
                    </div>

                    <flux:card class="space-y-6">
                        @if ($insights[$personality['id']])
                            <div id="markdown"><x-markdown>
                                {!! nl2br($insights[$personality['id']]->insight_text) !!}
                            </x-markdown></div>
                        @endif

                        @if (!$insights[$personality['id']])
                            <div class="mx-auto text-center">{{ $personality['name'] }} has not given a lesson on this question yet.</div>

                            @if ($personality['id'] > 0)
                                <div class="mx-auto my-6 text-center"><flux:button variant="primary" wire:click="summon({{ $question }}, {{ $personality['id'] }})">Summon {{ $personality['name'] }} for help</flux:button></div>
                            @endif
                        @endif
                    </flux:card>

                    @feature('insight_conversations')
                        @if($personality['id'] > 0 && $insights[$personality['id']])

                            <flux:card class="w-3/4 mx-auto my-4">
                                <flux:header>Ask {{ $personality['name']}} for Clarification</flux:header>

                                <div class="chat chat-start">
                                    <div class="chat-image avatar"><div class="w-16 rounded-full"><img src="{{ $personality['avatarUrl'] }}" /></div></div>
                                    <div class="chat-bubble chat-bubble-success"><div id="markdown">Hello there!</div></div>
                                </div>

                                <div class="chat chat-end">
                                    <div class="chat-image avatar"><div class="w-16 rounded-full"><img src="{{ auth()->user()->gravatarUrl() }}" /></div></div>
                                    <div class="chat-bubble chat-bubble-primary"><div id="markdown">How are you today?</div></div>
                                </div>

                                <flux:input class="my-6" />
                            </flux:card>
                        @endif
                    @endfeature

                </flux:tab.panel>
            @endforeach
        </flux:tab.group>
    </flux:modal>

</div>
