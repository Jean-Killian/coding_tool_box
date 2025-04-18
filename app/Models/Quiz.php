<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'question_count',
        'questions',
        'is_published',
    ];

    protected $casts = [
        'questions' => 'array',
        'is_published' => 'boolean',
    ];

    /**
     * Get the cohorts this quiz is assigned to.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cohorts()
    {
        return $this->belongsToMany(Cohort::class, 'cohorts_bilans');
    }

    /**
     * Scope a query to only include quizzes created by the given teacher.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $teacherId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTeacher($query, int $teacherId)
    {
        return $query->where('user_id', $teacherId);
    }

    /**
     * Get the students who have this quiz assigned (via a cohort).
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'cohorts_bilans')
            ->withPivot('score')
            ->withTimestamps();
    }

    /**
     * Get the teacher (user) who created the quiz.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include published quizzes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to only include draft (non-published) quizzes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDrafts($query)
    {
        return $query->where('is_published', false);
    }
}
