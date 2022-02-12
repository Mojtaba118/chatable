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

    public function senderChats()
    {
        return $this->morphMany(Chat::class, 'sender');
    }

    public function receiverChats()
    {
        return $this->morphMany(Chat::class, 'receiver');
    }

    public function chats()
    {
        return Chat::query()->where(function ($q) {
            $q->where('sender_id', $this->id)
                ->where('sender_type', static::class);

        })->orWhere(function ($q) {
            $q->where('receiver_id', $this->id)
                ->where('receiver_type', static::class);
        });
    }

    public function chatWith(Model $user)
    {
        return $this->senderChats()->create([
            'uuid' => Str::uuid()->toString(),
            'receiver_id' => $user->id,
            'receiver_type' => get_class($user),
        ]);
    }

//    public function messages(Chat $chat)
//    {
//        $chatExists = !!$this->chats()->where('id', $chat->id)->first();
//
//        if (!$chatExists)
//            throw new ChatNotFoundException();
//
//        $chat->load('messages.replies');
//
//        return $chat->messages;
//    }


//    public function sendMessage(Chat $chat, $data)
//    {
//        $chatExists = !!$this->chats()->where('id', $chat->id)->first();
//
//        if (!$chatExists)
//            throw new ChatNotFoundException();
//
//        return $chat->messages()->create(
//            [
//                'uuid' => Str::uuid()->toString(),
//                'content' => $data
//            ]
//        );
//    }

//    public function sendReply(Chat $chat, Message $message, $data)
//    {
//        $chatExists = !!$this->senderChats()->whereHas('messages', fn($q) => $q->where('id', $message->id))->where('id', $chat->id)->first();
//
//        if (!$chatExists)
//            throw new ChatNotFoundException();
//
//        return $message->replies()->create(
//            [
//                'uuid' => Str::uuid(),
//                'content' => $data
//            ]
//        );
//    }
}