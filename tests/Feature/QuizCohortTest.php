<?php

namespace Tests\Feature;

use App\Models\School;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Cohort;
use App\Models\Quiz;
use App\Models\User;

class QuizCohortTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_attach_quiz_to_cohort_with_user_and_score()
    {
        $cohort = Cohort::factory()->create();
        $quiz = Quiz::factory()->create();
        $user = User::factory()->create();
        $admin = User::factory()->create();
        $school = School::factory()->create(['user_id' => $admin->id]);

        $cohort->quizzes()->attach($quiz->id, [
            'user_id' => $user->id,
            'score' => null
        ]);

        $cohort->refresh();
        $quiz->refresh();
        $user->refresh();

        $this->assertTrue($cohort->quizzes->contains($quiz));
        $this->assertTrue($quiz->cohorts->contains($cohort));
        $this->assertTrue($user->assignedBilans->contains($quiz));

        $cohort->quizzes()->updateExistingPivot($quiz->id, [
            'user_id' => $user->id,
            'score' => 16
        ]);

        $pivotScore = $cohort->quizzes()->first()->pivot->score;
        $this->assertEquals(16, $pivotScore);
    }
}
