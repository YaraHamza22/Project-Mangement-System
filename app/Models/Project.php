<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
  use HasFactory;
protected $fillable = [
        'name', 'description', 'status', 'due_date', 'created_by_user_id', 'team_id'
    ];

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function attachments() {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'project_user')->withPivot('role')->withTimestamps();
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function managers() {
        return $this->users()->wherePivot('role', 'project_manager');
    }

    public function members() {
        return $this->users()->wherePivot('role', 'member');
    }

    public function scopeActive($query) {
        return $query->where('status', 'active');
    }

}
