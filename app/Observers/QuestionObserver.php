<?php

namespace App\Observers;

use App\Models\Answer;
use App\Models\Question;
use DB;

class QuestionObserver
{
    /**
     * Handle the Question "created" event.
     */
    public function created(Question $question): void
    {
        //
    }

    /**
     * Handle the Question "updated" event.
     */
    public function updated(Question $question): void
    {
        //
    }

    /**
     * Handle the Question "deleted" event.
     */
    public function deleted(Question $question): void
    {
        // Delete answers related to this question
        Answer::where('question_id', $question->id)->delete();

        // Delete the question records for a user
        DB::table('user_question')->where('question_id', $question->id)->delete();
    }

    /**
     * Handle the Question "restored" event.
     */
    public function restored(Question $question): void
    {
        //
    }

    /**
     * Handle the Question "force deleted" event.
     */
    public function forceDeleted(Question $question): void
    {
        //
    }
}
