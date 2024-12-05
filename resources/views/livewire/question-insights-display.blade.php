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
                    <flux:tab name="{{ $personality['name'] }}">{{ $personality['name'] }}</flux:tab>
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
                        <div class="mb-5 space-y-6">
                            <flux:heading size="lg">{{ $personality['name'] }}'s Explanation of the Question</flux:heading>
                        </div>

                        @if ($insights[$personality['id']])
                            {!! $insights[$personality['id']]->insight_text !!}
                        @else
                            {{ $personality['name'] }} has not given a lesson on this question yet.
                        @endif
                    </flux:card>
                </flux:tab.panel>
            @endforeach
        </flux:tab.group>

    </flux:modal>

</div>
