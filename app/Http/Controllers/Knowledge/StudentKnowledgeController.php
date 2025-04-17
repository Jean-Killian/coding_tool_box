<?php

namespace App\Http\Controllers\Knowledge;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentKnowledgeController
{
    public function answer(Quiz $quiz)
    {
        return view('pages.knowledge.answer', [
            'quiz' => $quiz
        ]);
    }

    public function submitAnswers(Request $request, Quiz $quiz)
    {
        $user = auth()->user();
        $answers = $request->input('answers', []);
        $score = 0;

        if (!$user) {
            abort(401, 'Vous devez être connecté.');
        }

        foreach ($quiz->questions as $index => $question) {
            if (isset($answers[$index]) && $answers[$index] === $question['answer']) {
                $score++;
            }
        }

        $cohort = $user->cohorts()
            ->whereHas('quizzes', fn($q) => $q->where('quizzes.id', $quiz->id))
            ->first();

        if (!$cohort) {
            return redirect()->route('knowledge.index')
                ->with('error', 'Impossible de trouver la cohorte liée à ce QCM.');
        }

        $alreadyAnswered = DB::table('cohorts_bilans')
            ->where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->whereNotNull('score')
            ->exists();

        if ($alreadyAnswered) {
            return redirect()->route('knowledge.index')
                ->with('error', 'Vous avez déjà répondu à ce QCM.');
        }

        DB::table('cohorts_bilans')
            ->where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->update([
                'score' => $score,
                'answers' => json_encode($answers),
                'updated_at' => now(),
            ]);

        //return redirect()->route('pages.knowledge.answer')->with('success', "Score enregistré : $score / " . count($quiz->questions));
        return redirect()->route('knowledge.quiz.result', $quiz->id);
    }

    public function result(Quiz $quiz)
    {
        $user = auth()->user();

        $bilan = DB::table('cohorts_bilans')
            ->where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->first();

        if (!$bilan || !$bilan->answers) {
            return redirect()->route('knowledge.index')
                ->with('error', 'Aucune réponse enregistrée pour ce QCM.');
        }

        return view('pages.knowledge.result', [
            'quiz' => $quiz->questions,
            'answers' => json_decode($bilan->answers, true),
            'score' => $bilan->score,
            'total' => $quiz->question_count,
        ]);
    }
}
