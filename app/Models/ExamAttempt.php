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

  public function exam()
  {
    return $this->belongsTo(Exam::class);
  }
  public function user()
  {
    return $this->belongsTo(User::class);
  }
  public function answers()
  {
    return $this->hasMany(ExamAnswer::class, 'attempt_id');
  }
}
