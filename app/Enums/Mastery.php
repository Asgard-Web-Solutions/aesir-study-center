<?php

namespace App\Enums;

enum Mastery: int
{
    case Unskilled = 0;
    case Apprentice = 1;
    case Familiar = 2;
    case Proficient = 3;
    case Mastered = 4;
}
