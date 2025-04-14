<?php

namespace App\Http\Controllers\Knowledge;

class StudentQuizController
{
    public function index()
    {
        $user = auth()->user();

        // Récupère les QCM assignés via une cohorte
        $quizzes = $user->bilans()->get();

        return view('student.quizzes.index', compact('quizzes'));
    }
}
