<div>
    <flux:modal.trigger name="view-insights">
        <flux:button class="btn btn-secondary" >Instructor Insights</flux:button>
    </flux:modal.trigger>

    <flux:modal name="view-insights" variant="flyout" class="w-full space-y-6 md:w-1/3">
        <div>
            <flux:heading size="lg">Question Mastery Insights</flux:heading>
            <flux:subheading>Explore this question and answer more in-depth.</flux:subheading>
        </div>
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
                            {!! nl2br($insights[$personality['id']]->insight_text) !!}
                        @endif

                        @if (!$insights[$personality['id']])
                            <div class="mx-auto text-center">{{ $personality['name'] }} has not given a lesson on this question yet.</div>

                            @if ($personality['id'] > 0)
                                <div class="mx-auto my-6 text-center"><flux:button variant="primary" wire:click="summon({{ $question }}, {{ $personality['id'] }})">Summon {{ $personality['name'] }} for help</flux:button></div>
                            @endif
                        @endif
                    </flux:card>
                </flux:tab.panel>
            @endforeach
        </flux:tab.group>

        <x-help.box>
            <x-help.text><x-help.highlight color="info">Acolyte Academy</x-help.highlight> has several instructors that are able to help you learn the material required to master your exams.</x-help.text>
            <x-help.text><x-help.highlight color="accent">Research Wizard Oddity</x-help.highlight> is a playful instructor that likes to use analogies and metaphors to help teach the concepts required in the question you are struggling with.</x-help.text>
            <x-help.text><x-help.highlight color="accent">Professor Bamboo</x-help.highlight> is more straight forward in his instructions. He doesn't use as many anologies in favor of just explaining things plainly.</x-help.text>
            <x-help.text>The Exam Author may have also provided their own insights for this question that may be of help for you.</x-help.text>
            <x-help.text>Choose whichever instructor helps you understand the content better! If you have any suggestions to improve the communication with the instructors make sure to <x-page.communitylink>share that with us on the Forums</x-page.communitylink>!</x-help.text>
        </x-help.box>

    </flux:modal>

</div>
