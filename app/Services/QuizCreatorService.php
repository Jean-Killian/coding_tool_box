<?php

namespace App\Services;

use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class QuizCreatorService
{
    public function create(array $data, array $questions)
    {
        return Quiz::create([
            'user_id' => Auth::id(),
            'subject' => $data['subject'],
            'question_count' => $data['question_count'],
            'questions' => $questions,
            'is_published' => $data['publish'] ?? false,
        ]);
    }
}
