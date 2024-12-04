<div>
    <flux:modal.trigger name="edit-insight">
        <flux:button>Edit Mastery Insight</flux:button>
    </flux:modal.trigger>

    <flux:modal name="edit-insight" class="w-full space-y-6 md:w-3/4">
        <div>
            <flux:heading size="lg">Update Mastery Insight</flux:heading>
            <flux:subheading>Give an explanation of this answer to those who may be struggling with the question.</flux:subheading>
        </div>

        <form wire:submit="save" class="space-y-6">

            <flux:textarea label="Mastery Insight" wire:model="insight_text" />

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
