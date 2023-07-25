<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'body',
        'status',
        'sent_at',
        'ref_code',
        'err_msg',
    ];
    
    protected $dates = ['sent_at'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    protected static function booted()
    {
        $events = ['creating', 'updating', 'saving', 'deleting'];
        foreach ($events as $event) {
            static::{$event}(function ($model) {
                Cache::tags($model->cache_tags)->flush();
            });
        }
    }
}
