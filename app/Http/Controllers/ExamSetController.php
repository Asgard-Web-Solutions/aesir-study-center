<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Set as ExamSet;
use Illuminate\Support\Facades\Auth;

class ExamSetController extends Controller
{
    public function view(ExamSet $exam): View
    {
        $this->authorize('view', $exam);

        $examRecord = null;

        if (Auth::check()) {
            $examRecord = DB::table('exam_records')
                            ->where('set_id', $exam->id)
                            ->where('user_id', Auth::id())
                            ->first();
        }

        return view('exam.view')->with([
            'exam' => $exam,
            'examRecord' => $examRecord,
        ]);
    }

    public function public(): View
    {
        $exams = ExamSet::where('visibility', 1)->get();

        return view('exam.public')->with([
            'exams' => $exams,
        ]);
    }

}
