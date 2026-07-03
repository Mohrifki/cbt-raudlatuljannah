<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlotSession extends Model
{
    protected $fillable = ['plot', 'grade', 'label', 'start_at', 'end_at'];
    protected $casts = ['start_at' => 'datetime', 'end_at' => 'datetime'];
}