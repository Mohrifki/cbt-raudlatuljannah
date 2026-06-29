<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'type'];

    public function students()
    {
        return $this->belongsToMany(User::class, 'student_subject', 'subject_id', 'user_id')->withTimestamps();
    }

    public function questions()
    {
        return $this -> hasMany(Question::class);
    }
}
