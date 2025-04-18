<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class QuizStudentService
{
    /**
     * Retrieves quizzes assigned to the user's cohorts that they haven't completed yet.
     *
     * @param User $user The authenticated student.
     * @return \Illuminate\Support\Collection List of assigned quizzes.
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
     * Retrieves all quizzes the user has already completed.
     *
     * @param User $user The authenticated student.
     * @return \Illuminate\Support\Collection List of completed quizzes with scores.
     */
    public function getCompletedQuizzes(User $user)
    {
        return $user->quizzes()->wherePivotNotNull('score')->get();
    }


    /**
     * Retrieves quizzes created by the student for self-training.
     *
     * @param User $user The authenticated student.
     * @return \Illuminate\Support\Collection List of self-generated quizzes.
     */
    public function getSelfGeneratedQuizzes(User $user)
    {
        return Quiz::where('user_id', $user->id)->get();
    }

    /**
     * Calculates the quiz score based on provided answers.
     *
     * @param Quiz $quiz The quiz to be evaluated.
     * @param array $answers User-submitted answers.
     * @return int The score obtained by the student.
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
     * Finds the cohort associated with both the user and the given quiz.
     *
     * @param User $user The authenticated student.
     * @param Quiz $quiz The quiz to match against.
     * @return \App\Models\Cohort|null The matched cohort or null if not found.
     */
    public function getCohortForQuiz(User $user, Quiz $quiz)
    {
        return $user->cohorts()
            ->whereHas('quizzes', fn($q) => $q->where('quizzes.id', $quiz->id))
            ->first();
    }

    /**
     * Checks if the user has already submitted answers for the quiz.
     *
     * @param User $user The authenticated student.
     * @param Quiz $quiz The quiz to check.
     * @return bool True if already answered, false otherwise.
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
     * Stores the user's quiz result, including score and answers.
     *
     * @param User $user The authenticated student.
     * @param Quiz $quiz The quiz that was completed.
     * @param int $score The score obtained.
     * @param array $answers The submitted answers.
     * @return void
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
     * Retrieves the user's result for a specific quiz.
     *
     * @param User $user The authenticated student.
     * @param Quiz $quiz The quiz to get results for.
     * @return object|null The result record or null if not found.
     */
    public function getStudentResult(User $user, Quiz $quiz): ?object
    {
        return DB::table('cohorts_bilans')
            ->where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->first();
    }
}

