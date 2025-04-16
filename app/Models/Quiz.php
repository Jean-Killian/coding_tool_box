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

    public function cohorts()
    {
        return $this->belongsToMany(Cohort::class, 'cohorts_bilans');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'cohorts_bilans')
            ->withPivot('score')
            ->withTimestamps();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
