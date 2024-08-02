@extends('layouts.app2')

@section('content')
    <x-page.header :text="$question->set->name" />

    <x-card.main title="Edit Question">
        <x-card.mini>
            <form action="{{ route('update-question', $question->id) }}" method="post">
                @csrf
    
                <x-form.text name="text" label="Question" value="{!! old('text', $question->text) !!}" />
       
                <x-card.buttons submitLabel="Update Question" />
            </form>    
        </x-card.mini>
    </x-card.main>

    <x-card.buttons primaryLabel="Return to Question List" primaryAction="{{ route('manage-answers', $question->id) }}" />
@endsection
