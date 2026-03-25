<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskActivity extends Model
{
  use HasFactory;
  use HasUuids;

  /**
   * @var list<string>
   */
  protected $fillable = [
    'task_id',
    'user_id',
    'comment',
  ];

  public function task(): BelongsTo
  {
    return $this->belongsTo(Task::class);
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
