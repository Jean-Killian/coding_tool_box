<?php

use App\Http\Controllers\CohortController;
use App\Http\Controllers\CommonLifeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\Knowledge\IAQuizGeneratorController;
use App\Http\Controllers\Knowledge\QuizAssignmentController;
use App\Http\Controllers\Knowledge\QuizCreationController;
use App\Http\Controllers\Knowledge\StudentQuizController;
use App\Http\Controllers\Knowledge\TeacherQuizController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RetroController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// Redirect the root path to /dashboard
Route::redirect('/', 'dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('verified')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Cohorts
        Route::get('/cohorts', [CohortController::class, 'index'])->name('cohort.index');
        Route::get('/cohort/{cohort}', [CohortController::class, 'show'])->name('cohort.show');

        // Teachers
        Route::get('/teachers', [TeacherController::class, 'index'])->name('teacher.index');

        // Students
        Route::get('students', [StudentController::class, 'index'])->name('student.index');

        // Knowledge
        Route::prefix('knowledge')->name('knowledge.')->group(function () {
            Route::get('/', [StudentQuizController::class, 'index'])->name('index');
            Route::get('/teacher_index', [TeacherQuizController::class, 'indexTeacher'])->name('teacher_index');
            Route::get('/generate', [QuizCreationController::class, 'showQuizCreationForm'])->name('generate');
            Route::get('/generate-ia', [IAQuizGeneratorController::class, 'generateQuizFromUserInput'])->name('ia.generate');
            Route::get('/preview', [TeacherQuizController::class, 'previewGeneratedQuiz'])->name('preview');
            Route::post('/store', [QuizCreationController::class, 'saveGeneratedQuiz'])->name('store');
            Route::get('/quiz/show/{quiz}', [TeacherQuizController::class, 'show'])->name('quiz.show');
            Route::get('/quiz/answer/{quiz}', [StudentQuizController::class, 'answer'])->name('quiz.answer');
            Route::post('/quiz/answer/{quiz}', [StudentQuizController::class, 'submitAnswers'])->name('quiz.submit');
            Route::post('/assign-quiz', [QuizAssignmentController::class, 'assign'])->name('assign.quiz');
            Route::get('/assign-quiz', [TeacherQuizController::class, 'showAssignForm'])->name('assign.quiz.form');
            Route::get('/temp-answer', [IAQuizGeneratorController::class, 'showTempAnswerForm'])->name('temp.answer');
            Route::post('/temp-answer', [IAQuizGeneratorController::class, 'submitTempAnswers'])->name('temp.submit');
            Route::get('/quiz/result/{quiz}', [StudentQuizController::class, 'result'])->name('quiz.result');
            Route::delete('/quiz/{quiz}', [TeacherQuizController::class, 'destroy'])->name('quiz.delete');
        });

        // Groups
        Route::get('groups', [GroupController::class, 'index'])->name('group.index');

        // Retro
        route::get('retros', [RetroController::class, 'index'])->name('retro.index');

        // Common life
        Route::get('common-life', [CommonLifeController::class, 'index'])->name('common-life.index');
    });

});

require __DIR__.'/auth.php';
