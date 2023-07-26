<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Components\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function messages_from_me(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messages_to_me(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function scopeWithAll($query)
    {
        $query->with(['messages_from_me', 'messages_to_me']);
    }

    public function scopeFilterBy($query, $filters): Builder
    {
        $namespace = 'App\Http\Filters\User';
        $filter = new FilterBuilder($query, $filters, $namespace);
        return $filter->apply();
    }
}
