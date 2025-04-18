<?php

namespace App\Services;

use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class QuizCreatorService
{
    /**
     * Creates a new quiz with the given data and questions.
     *
     * @param array $data      An associative array containing 'subject', 'question_count', and optionally 'publish'.
     * @param array $questions An array of structured questions to be stored as JSON.
     *
     * @return \App\Models\Quiz The newly created Quiz instance.
     */

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
