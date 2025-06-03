<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'priority_id',
        'status_id',
        'due_date',
        'user_id',
        'share_token',
        'share_token_expires_at',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'share_token_expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function histories()
    {
        return $this->hasMany(TaskHistory::class);
    }

    public function isShareTokenValid(): bool
    {
        return filled($this->share_token)
            && $this->share_token_expires_at instanceof Carbon
            && now()->lt($this->share_token_expires_at);
    }
}
