<?php

return [
    'min_score' => env('MINIMUM_SCORE', 0),
    'add_score' => env('SCORE_CORRECT', 1),
    'sub_score' => env('SCORE_INCORRECT', 1),
    'hour_multiplier' => env('HOUR_MULTIPLIER', 3),

    'count_tests_for_average_score' => env('AVG_SCORE_COUNT_TESTS', 3),

    'grade_mastery' => env('GRADE_MASTERY', 8),
    'grade_proficient' => env('GRADE_PROFICIENT', 4),
    'grade_familiar' => env('GRADE_FAMILIAR', 2),
];