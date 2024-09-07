<?php

return [
    'min_score' => env('MINIMUM_SCORE', 1),
    'add_score' => env('SCORE_CORRECT', 1),
    'sub_score' => env('SCORE_INCORRECT', 1),
    'hour_multiplier' => env('HOUR_MULTIPLIER', 3),

    'count_tests_for_average_score' => env('AVG_SCORE_COUNT_TESTS', 3),

    'grade_mastered' => env('GRADE_MASTERED', 9),
    'grade_proficient' => env('GRADE_PROFICIENT', 6),
    'grade_familiar' => env('GRADE_FAMILIAR', 3),
    'grade_apprentice' => env('GRADE_APPRENTICE', 1),

    'target_answers' => env('TARGET_ANSWERS', 4),

    'min_public_questions' => env('MIN_PUBLIC_QUESTIONS', 50),
    'max_exam_questions' => env('MAX_EXAM_QUESTIONS', 250),

    'add_proficient_architect_credits' => 0.2,
    'add_proficient_study_credits' => 0.5,

    'add_mastered_architect_credits' => 0.5,
    'add_mastered_study_credits' => 1,

    'award_the_architect_architect_credits' => 2,
    'award_the_architect_study_credits' => 2,
];
