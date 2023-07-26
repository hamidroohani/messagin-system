<?php

namespace App\Http\Repositories;

use App\Components\Repositories\CRUD;
use App\Models\Message;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class MessageRepository extends CRUD
{
    public function __construct(private Message $message)
    {
        parent::__construct($this->message);
    }

    public function last_messages(): LengthAwarePaginator
    {
        return $this->message->select('id', 'sender_id', 'receiver_id', 'body', 'created_at')
            ->where(function ($query) {
                $query->whereIn('sender_id', User::pluck('id'))
                    ->orWhereIn('receiver_id', User::pluck('id'));
            })
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('messages')
                    ->whereRaw('messages.sender_id = sender_id OR messages.receiver_id = sender_id')
                    ->groupBy('sender_id');
            })
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('messages')
                    ->whereRaw('messages.sender_id = receiver_id OR messages.receiver_id = receiver_id')
                    ->groupBy('receiver_id');
            })
            ->paginate();
    }

    public function last_saved_message()
    {
        return $this->message->query()->where('sender_id', auth()->id())->where('receiver_id', auth()->id())->latest()->first();
    }

    public function get_direct_messages($participant_id)
    {
        if ($participant_id !== auth()->id()){
            return $this->message->query()->whereColumn('sender_id', '!=', 'receiver_id')
                ->where(function ($query) use ($participant_id) {
                    $query->where('sender_id', $participant_id)->orWhere('receiver_id', $participant_id);
                })
                ->limit(100)->get();
        }

        return $this->message->query()->where('sender_id', auth()->id())->where('receiver_id', auth()->id())->limit(100)->get();
    }
}
