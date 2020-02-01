<?php

return [
    'min_score' => env('MINIMUM_SCORE', 0),
    'add_score' => env('SCORE_CORRECT', 1),
    'sub_score' => env('SCORE_INCORRECT', 1),
    'hour_multiplier' => env('HOUR_MULTIPLIER', 3),
];