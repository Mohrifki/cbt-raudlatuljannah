<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
  protected $fillable = ['exam_id', 'user_id', 'started_at', 'finished_at', 'status', 'score', 'violation_count', 'question_order'];

  protected $casts = [
    'started_at'     => 'datetime',
    'finished_at'    => 'datetime',
    'question_order' => 'array',
  ];

  public function user()
  {
    return $this->belongsTo(\App\Models\User::class, 'user_id');
  }
  public function exam()
  {
    return $this->belongsTo(\App\Models\Exam::class, 'exam_id');
  }
  public function answers()
  {
    return $this->hasMany(\App\Models\ExamAnswer::class, 'attempt_id');
  }
}
