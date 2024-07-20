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

    'color_mastered' => 'accent',
    'color_proficient' => 'secondary',
    'color_familiar' => 'success',
    'color_apprentice' => 'info',

    'target_answers' => env('TARGET_ANSWERS', 4),
];
