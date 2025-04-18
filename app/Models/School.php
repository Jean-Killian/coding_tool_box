<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory;

    protected $table        = 'schools';
    protected $fillable     = ['user_id', 'name', 'description'];
}
