<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class QuizStudentService
{
    /**
     * Retourne les QCM assignés à un étudiant via ses cohorts,
     * et auxquels il n’a pas encore répondu.
     */
    public function getAssignedQuizzes(User $user)
    {
        return Quiz::whereHas('cohorts', function ($query) use ($user) {
            $query->whereIn('cohorts.id', $user->cohorts->pluck('id'));
        })
            ->whereDoesntHave('students', function ($query) use ($user) {
                $query->where('user_id', $user->id)->whereNotNull('score');
            })
            ->get();
    }

    /**
     * Retourne les QCM auxquels l'étudiant a déjà répondu.
     */
    public function getCompletedQuizzes(User $user)
    {
        return $user->quizzes()->wherePivotNotNull('score')->get();
    }

    /**
     * Retourne les QCM que l'étudiant a générés lui-même.
     */
    public function getSelfGeneratedQuizzes(User $user)
    {
        return Quiz::where('user_id', $user->id)->get();
    }

    /**
     * Calcule le score obtenu à partir des réponses de l'étudiant.
     */
    public function calculateScore(Quiz $quiz, array $answers): int
    {
        $score = 0;
        foreach ($quiz->questions as $index => $question) {
            if (isset($answers[$index]) && $answers[$index] === $question['answer']) {
                $score++;
            }
        }
        return $score;
    }

    /**
     * Retourne la cohorte de l'étudiant liée au QCM donné.
     */
    public function getCohortForQuiz(User $user, Quiz $quiz)
    {
        return $user->cohorts()
            ->whereHas('quizzes', fn($q) => $q->where('quizzes.id', $quiz->id))
            ->first();
    }

    /**
     * Vérifie si l'étudiant a déjà répondu à ce QCM.
     */
    public function hasAlreadyAnswered(User $user, Quiz $quiz): bool
    {
        return DB::table('cohorts_bilans')
            ->where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->whereNotNull('score')
            ->exists();
    }

    /**
     * Enregistre les réponses et le score dans la base de données.
     */
    public function storeStudentResult(User $user, Quiz $quiz, int $score, array $answers): void
    {
        DB::table('cohorts_bilans')
            ->where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->update([
                'score' => $score,
                'answers' => json_encode($answers),
                'updated_at' => now(),
            ]);
    }

    /**
     * Récupère les données du bilan d’un étudiant pour un QCM donné.
     */
    public function getStudentResult(User $user, Quiz $quiz): ?object
    {
        return DB::table('cohorts_bilans')
            ->where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->first();
    }
}

