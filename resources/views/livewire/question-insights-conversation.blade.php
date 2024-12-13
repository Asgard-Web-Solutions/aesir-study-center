<div>
    <flux:card class="w-3/4 mx-auto my-4">
        <flux:header class="text-xl">Ask {{ $personality['name']}} for Clarification</flux:header>

        {{-- Create a button to Start a conversation --}}

        @if ($conversation)
            <div class="chat chat-start">
                <div class="chat-image avatar"><div class="w-16 rounded-full"><img src="{{ $personality['avatarUrl'] }}" /></div></div>
                <div class="chat-bubble chat-bubble-success"><div id="markdown">Hello there!</div></div>
            </div>

            <div class="chat chat-end">
                <div class="chat-image avatar"><div class="w-16 rounded-full"><img src="{{ auth()->user()->gravatarUrl() }}" /></div></div>
                <div class="chat-bubble chat-bubble-primary"><div id="markdown">How are you today?</div></div>
            </div>
        @endif

        <flux:input class="my-6" />
    </flux:card>

    {{-- List older conversations --}}
</div>
