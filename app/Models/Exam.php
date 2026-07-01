<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
  use HasFactory;

  protected $fillable = [
    'title',
    'subject_id',
    'description',
    'type',
    'grade',
    'duration',
    'start_at',
    'end_at',
    'shuffle_questions',
    'shuffle_options',
    'question_count',
    'status',
    'created_by',
  ];

  protected $casts = [
    'start_at' => 'datetime',
    'end_at' => 'datetime',
    'shuffle_questions' => 'boolean',
    'shuffle_options' => 'boolean',
  ];

  public function subject()
  {
    return $this->belongsTo(Subject::class);
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function classes()
  {
    return $this->belongsToMany(SchoolClass::class, 'exam_class', 'exam_id', 'class_id');
  }

  public function questions()
  {
    return $this->belongsToMany(Question::class, 'exam_question', 'exam_id', 'question_id')
      ->withPivot('order')->orderBy('order');
  }
}
