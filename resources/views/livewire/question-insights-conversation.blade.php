<div>
    @if ($conversation)
        <flux:card class="w-full mx-auto my-4">
            <flux:header class="text-xl">Ask {{ $personality['name']}} for Clarification on this Question &amp; Insight</flux:header>

            {{-- Create a button to Start a conversation --}}

            @if ($conversation)
                @foreach ($conversation->dialogs as $dialog)
                    @php
                        $side = "";
                        $color = "";
                        $avatar = "";

                        if ($dialog->personality_id) {
                            $side = "chat-start";
                            $color="chat-bubble->success";
                            $avatar = $personality['avatarUrl'];
                        } else {
                            if ($dialog->user_id == auth()->id()) {
                                $side = "chat-end";
                                $color = "chat-bubble-primary";
                                $avatar = auth()->user()->gravatarUrl();
                            } else {
                                $side = "chat-start";
                                $color = "chat-bubble-warning";
                                $avatar = "";
                            }
                        }
                    @endphp

                    <div class="chat {{ $side }} mb-6">
                        <div class="chat-image avatar"><div class="w-16 rounded-full"><img src="{{ $avatar }}" /></div></div>
                        <div class="chat-bubble {{ $color }}"><div id="markdown"><x-markdown>{{ $dialog->message }}</x-markdown></div></div>
                    </div>
                @endforeach
            @endif

            <flux:input.group>
                <flux:input wire:model="textMessage" wire:keydown.enter="SendMessage({{ $conversation }})" id="scroll-to" />
                <flux:button wire:click="SendMessage({{ $conversation }})" variant="primary">Send Message</flux:button>
            </flux:input.group>

        </flux:card>
    @else
        <div class="flex w-full my-6 text-center">
            <flux:button wire:click="createConversation({{ $insight }})" class="mx-auto">Start Conversation with {{ $personality['name'] }}</flux:button>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:load', function () {
            // Listen for the 'messageSent' event triggered from Livewire
            Livewire.on('ScrollTo', () => {
                document.getElementById('scroll-to').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>

    {{-- List older conversations --}}
</div>
