<?php

namespace App\Http\Repositories;

use App\Components\Repositories\CRUD;
use App\Models\Message;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MessageRepository extends CRUD
{
    public function __construct(private Message $message)
    {
        parent::__construct($this->message);
    }

    public function last_messages(): LengthAwarePaginator
    {
        return $this->message->whereIn('id', function ($query) {
            $query->selectRaw('MAX(id)')
                ->from('messages')
                ->where(function ($query) {
                    $query->where('sender_id', auth()->id())
                        ->orWhere('receiver_id', auth()->id());
                })
                ->whereColumn('sender_id', '!=', 'receiver_id')
                ->groupBy(DB::raw('CASE WHEN sender_id < receiver_id THEN sender_id ELSE receiver_id END'))
                ->groupBy(DB::raw('CASE WHEN sender_id < receiver_id THEN receiver_id ELSE sender_id END'));
            })
            ->orderByDesc('created_at')
            ->paginate();
    }

    public function last_saved_message()
    {
        return $this->message->query()->where('sender_id', auth()->id())->where('receiver_id', auth()->id())->latest()->first();
    }

    public function get_direct_messages($participant_id)
    {
        if ($participant_id !== auth()->id()) {
            return $this->message->query()->whereColumn('sender_id', '!=', 'receiver_id')
                ->where(function ($query) use ($participant_id) {
                    $query->where('sender_id', $participant_id)->orWhere('receiver_id', $participant_id);
                })
                ->limit(100)->get();
        }

        return $this->message->query()->where('sender_id', auth()->id())->where('receiver_id', auth()->id())->limit(100)->get();
    }
}
