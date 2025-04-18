<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cohort extends Model
{
    use HasFactory;

    protected $table        = 'cohorts';
    protected $fillable     = ['school_id', 'name', 'description', 'start_date', 'end_date'];

    /**
     * Get the users that belong to the cohort.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'cohort_user')->withTimestamps();
    }

    /**
     * Get the quizzes that are assigned to this cohort.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'cohorts_bilans')
            ->withPivot(['user_id', 'score'])
            ->withTimestamps();
    }
}
