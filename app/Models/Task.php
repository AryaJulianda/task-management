<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
  use HasFactory;
  use HasUuids;
  use SoftDeletes;

  /**
   * @var list<string>
   */
  protected $fillable = [
    'project_id',
    'title',
    'description',
    'status',
    'priority',
    'due_date',
    'created_by',
  ];

  /**
   * @var array<string, string>
   */
  protected $casts = [
    'due_date' => 'date',
  ];

  public function project(): BelongsTo
  {
    return $this->belongsTo(Project::class);
  }

  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function assignees(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'task_assignees')
      ->withPivot('assigned_at');
  }

  public function activities(): HasMany
  {
    return $this->hasMany(TaskActivity::class);
  }
}
