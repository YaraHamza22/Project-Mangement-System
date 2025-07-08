<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     *
     */
    protected $guard_name ='sanctum';

    protected $fillable = [
        'name',
        'email',
        'password',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function teams() {
        return $this->belongsToMany(Team::class, 'team_user')->withTimestamps();
    }

    public function projects() {
        return $this->belongsToMany(Project::class, 'project_user')->withPivot('role')->withTimestamps();
    }

    public function ownedTeams() {
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function createdProjects() {
        return $this->hasMany(Project::class, 'created_by_user_id');
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function assignedTasks() {
        return $this->hasMany(Task::class, 'assigned_to_user_id');
    }
}
