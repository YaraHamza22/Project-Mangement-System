<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

      protected $fillable = [
        'user_id', 'commentable_id', 'commentable_type', 'content'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function commentable() {
        return $this->morphTo();
    }

    public function attachments() {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
