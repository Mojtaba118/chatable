<?php

namespace Mojtaba\Chatable\Traits;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Mojtaba\Chatable\Exceptions\ChatNotFoundException;
use Mojtaba\Chatable\Models\Chat;
use Mojtaba\Chatable\Models\Message;

trait Chatable
{
    use HasRelationships;

    public function privateChats()
    {
        return $this->morphMany(static::class, 'sender');
    }

    public function messages(Chat $chat)
    {
        $chatExists = !!$this->privateChats()->where('id', $chat->id)->first();

        if (!$chatExists)
            throw new ChatNotFoundException();

        $chat->load('messages.replies');

        return $chat->messages;
    }

    public function chatWith(Model $user)
    {
        return $this->privateChats()->create([
            'receiver_id' => $user->id,
            'receiver_type' => get_class($user),
        ]);
    }

    public function sendMessage(Chat $chat, $data)
    {
        $chatExists = !!$this->privateChats()->where('id', $chat->id)->first();

        if (!$chatExists)
            throw new ChatNotFoundException();

        return $chat->messages()->create(
            [
                'uuid' => Str::uuid(),
                'content' => $data
            ]
        );
    }

    public function sendReply(Chat $chat, Message $message, $data)
    {
        $chatExists = !!$this->privateChats()->whereHas('messages', fn($q) => $q->where('id', $message->id))->where('id', $chat->id)->first();

        if (!$chatExists)
            throw new ChatNotFoundException();

        return $message->replies()->create(
            [
                'uuid' => Str::uuid(),
                'content' => $data
            ]
        );
    }
}