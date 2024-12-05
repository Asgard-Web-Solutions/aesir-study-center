<div>
    <flux:modal.trigger name="view-insights">
        <flux:button >Mastery Insights</flux:button>
    </flux:modal.trigger>

    <flux:modal name="view-insights" variant="flyout" class="w-1/3 space-y-6">
        <div>
            <flux:heading size="lg">Question Mastery Insights</flux:heading>
            <flux:subheading>Explore this question and answer more in-depth.</flux:subheading>
        </div>

        <flux:tab.group>
            <flux:tabs>
                @foreach ($personalities as $personality)
                    @if ($personality['id'] > 0 || $insights[$personality['id']])
                        <flux:tab name="{{ $personality['name'] }}">{{ $personality['name'] }}</flux:tab>
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
                            {{ $personality['name'] }} has not given a lesson on this question yet.

                            @if ($personality['id'] > 0)
                                <div><flux:button variant="primary" wire:click="summon({{ $question }}, {{ $personality['id'] }})">Summon {{ $personality['name'] }}</flux:button></div>
                            @endif
                        @endif
                    </flux:card>
                </flux:tab.panel>
            @endforeach
        </flux:tab.group>

    </flux:modal>

</div>
