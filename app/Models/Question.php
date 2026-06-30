<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
  use HasFactory;

  protected $fillable = [
    'subject_id',
    'created_by',
    'type',
    'question',
    'option_a',
    'option_b',
    'option_c',
    'option_d',
    'option_e',
    'correct_option',
    'language',
    'starter_code',
    'answer_key',
    'score'
  ];

  public function subject()
  {
    return $this -> belongsTo(subject::class);
  }

  public function creator()
  {
    return $this -> belongsTo(User::class, 'created_by');
  }
}
