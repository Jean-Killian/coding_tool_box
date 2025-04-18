<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * This function returns the full name of the connected user
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->last_name . ' ' . $this->first_name;
    }

    /**
     * This function returns the short name of the connected user
     * @return string
     */
    public function getShortNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name[0] . '.';
    }

    /**
     * Defines the many-to-many relationship between the user and schools.
     * Includes the role of the user in the school and timestamps.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function schools()
    {
        return $this->belongsToMany(School::class, 'users_schools')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Returns the first associated school of the user.
     * Useful when a user is only expected to belong to one school.
     */
    public function school()
    {
        return $this->schools()->first();
    }

    /**
     * Defines the many-to-many relationship between the user and cohorts.
     * Represents the classes or groups the user is part of.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cohorts()
    {
        return $this->belongsToMany(Cohort::class, 'cohort_user')->withTimestamps();
    }

    /**
     * Defines the many-to-many relationship between the user and quizzes.
     * Includes the user's score and timestamps for when they took the quiz.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'cohorts_bilans')
            ->withPivot('score')
            ->withTimestamps();
    }

    /**
     * Check if the user has a 'teacher' role in at least one of their schools.
     *
     * @return bool
     */
    public function isTeacher(): bool
    {
        return $this->schools()->wherePivot('role', 'teacher')->exists();
    }
}
