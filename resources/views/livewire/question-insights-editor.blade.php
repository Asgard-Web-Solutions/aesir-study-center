<div>

    <x-card.main>
        <flux:tab.group>
            <flux:tabs>
                <flux:tab name="Author"><x-user.avatar size="tiny">{{ $question->set->user->gravatarUrl() }}</x-user.avatar> {{ $question->set->user->name }}</flux:tab>

                @foreach($personalities as $personality)
                    <flux:tab name="Instructor-{{ $personality['id'] }}"><x-user.avatar size="tiny">{{ $personality['avatarUrl'] }}</x-user.avatar> {{ $personality['name'] }}</flux:tab>
                @endforeach
            </flux:tabs>

            <flux:tab.panel name="Author">
                <div>
                    <flux:heading size="lg">Update Mastery Insight</flux:heading>
                    <flux:subheading>Give an explanation of this answer to those who may be struggling with the question. If an acolyte asks for an Instructor to give their insights on the question then they will take your Insight into consideration when deciding on their answer.</flux:subheading>
                </div>

                <form wire:submit="save" class="space-y-6">

                    <flux:textarea label="Mastery Insight" wire:model="insight_text" />

                    <div class="flex">
                        <flux:spacer />

                        <flux:button type="submit" variant="primary">Save changes</flux:button>
                    </div>
                </form>
            </flux:tab.panel>

            @foreach ($personalities as $personality)
                <flux:tab.panel name="Instructor-{{ $personality['id'] }}">
                    <flux:card class="space-y-6 text-left">
                        @if($instructorInsights[$personality['id']] != null)
                            <div id="markdown"><x-markdown>
                                {!! nl2br($instructorInsights[$personality['id']]->insight_text) !!}
                            </x-markdown></div>

                            <flux:button
                                variant="danger"
                                wire:click="deleteInsight({{ $question }}, {{ $instructorInsights[$personality['id']]->id }})"
                                wire:confirm="Are you sure you want to delete this instructor's Insight? This cannot be undone."
                            >Delete Insight</flux:button>

                        @else
                            This instructor has not yet recorded an Insight for this question.
                        @endif
                    </flux:card>
                </flux:tab.panel>
            @endforeach
        </flux:tab.group>
    </x-card.main>
</div>
