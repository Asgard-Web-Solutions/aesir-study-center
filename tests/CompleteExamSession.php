<?php

namespace Tests;

use Carbon\Carbon;
use DB;

trait CompleteExamSession
{
    public function CompleteExamSession($session)
    {
        $updateSession['current_question'] = $session->question_count;
        $updateSession['correct_answers'] = ceil($session->question_count / 2);
        $updateSession['incorrect_answers'] = floor($session->question_count / 2);
        $updateSession['date_completed'] = Carbon::now();

        DB::table('exam_sessions')->where('id', $session->id)->update($updateSession);
        $session = DB::table('exam_sessions')->where('id', $session->id)->first();

        return $session;
    }
}
