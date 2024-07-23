<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamPractice extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'exam_id', 'question_count', 'question_index', 'question_order'
    ];
}
