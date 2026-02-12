<div>
    <x-card.main title='Test Settings'>
        <form action="{{ route('exam-session.store', $examSet) }}" method="post">
            @csrf
            
            @if ($examSet->multi_lesson_exam && count($examSet->lessons) > 0)
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-bold">Select Lesson</label>
                    <select wire:model.live="selectedLessonId" name="lesson_id" class="w-full select select-bordered">
                        <option value="">All Lessons</option>
                        @foreach ($examSet->lessons as $lesson)
                            <option value="{{ $lesson->id }}">{{ $lesson->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <x-form.text 
                name='question_count' 
                label='How Many Questions?' 
                helptext='Available Questions: {{ $maxQuestions }} / {{ $selectedLessonId ? "Lesson Question Pool" : "Question Pool" }}: {{ $questionPoolCount }}' 
                value='{{ $questionCount }}'
            />
            
            <x-card.buttons submitLabel='Begin Test' />
        </form>
    </x-card.main>
</div>
