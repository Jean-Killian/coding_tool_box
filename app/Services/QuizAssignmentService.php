<?php

namespace App\Services;

use App\Models\Cohort;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;

class QuizAssignmentService
{
    /**
     * Assigns a quiz to all students within a specific cohort.
     *
     * - Ensures that each student receives an entry in the `cohorts_bilans` table.
     * - If an assignment already exists for a student, it is updated with the current timestamps.
     * - Publishes the quiz by setting `is_published` to true.
     *
     * @param int $quizId   The ID of the quiz to assign.
     * @param int $cohortId The ID of the cohort to assign the quiz to.
     *
     * @return void
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *         If the quiz or cohort does not exist.
     */
    public function assignQuizToCohort(int $quizId, int $cohortId): void
    {
        $quiz = Quiz::findOrFail($quizId);
        $cohort = Cohort::findOrFail($cohortId);

        foreach ($cohort->users as $student) {
            DB::table('cohorts_bilans')->updateOrInsert([
                'user_id'    => $student->id,
                'quiz_id'    => $quiz->id,
                'cohort_id'  => $cohort->id,
            ], [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $quiz->update(['is_published' => true]);
    }
}
