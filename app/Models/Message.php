<?php

namespace App\Models;

use App\Components\Filters\FilterBuilder;
use App\Enums\MessageStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
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

    public array $cache_tags = ['messages'];

    protected $casts = [
        'status' => MessageStatus::class,
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function scopeWithAll($query)
    {
        $query->with(['sender', 'receiver']);
    }

    public function scopeFilterBy($query, $filters): Builder
    {
        $namespace = 'App\Http\Filters\Message';
        $filter = new FilterBuilder($query, $filters, $namespace);
        return $filter->apply();
    }
}
