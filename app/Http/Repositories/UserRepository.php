<?php

namespace App\Http\Repositories;

use App\Components\Repositories\CRUD;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends CRUD
{
    public function __construct(private User $user)
    {
        parent::__construct($this->user);
    }

    public function all_users_not_me(): LengthAwarePaginator
    {
        $fillables = $this->user->getFillable();
        $filters = [];

        foreach ($fillables as $fillable) {
            $filters[$fillable] = request()->input($fillable);
        }

        return $this->user->withAll()->where('id','!=', auth()->id())->filterBy($filters)->latest()->paginate();
    }

    public function find_by_email(?string $email)
    {
        $user = User::query()->where('email', $email)->first();
        if (!$user) {
            $user = auth()->user();
            $user->name = "Saved Messages";
        }

        return $user;
    }

    public function search($q)
    {
        return $this->user
            ->where('id', '!=', auth()->id())
            ->where('email', 'like', '%' . $q . "%")
            ->limit(5)
            ->get();
    }
}
