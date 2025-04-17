<?php

namespace App\Services;

use App\Models\Cohort;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;

class QuizAssignmentService
{
    /**
     * Affecte un QCM à tous les étudiants d'une cohorte.
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
