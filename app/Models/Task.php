<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
  use HasFactory;

    protected $fillable = [
        'name', 'description', 'status', 'priority', 'due_date', 'project_id', 'assigned_to_user_id'
    ];

   public function project() {
        return $this->belongsTo(Project::class);
    }

    public function assignee() {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function attachments() {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function getStatusLabelAttribute() {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    public function setNameAttribute($value) {
        $this->attributes['name'] = trim($value);
    }

    public function scopeOverdue($query) {
        return $query->where('due_date', '<', now())->where('status', '!=', 'completed');
    }
}
