<?php

namespace Mojtaba\Chatable\Traits;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Model;
use Mojtaba\Chatable\Events\SendChatMessageEvent;
use Mojtaba\Chatable\Exceptions\ChatNotFoundException;
use Mojtaba\Chatable\Models\Chat;
use Mojtaba\Chatable\Models\Message;
use Mojtaba\Chatable\Services\ChatMessageService;
use Mojtaba\Chatable\Services\ChatService;

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

    public function messages()
    {
        return $this->morphMany(Message::class, 'sender');
    }

    public function chats()
    {
        return ChatService::chats($this);
    }

    public function chatWith(Model $user)
    {
        return ChatService::chatWith($this, $user);
    }

    public function chatMessages(Chat $chat)
    {
        if (!ChatService::chatExists($this, $chat))
            throw new ChatNotFoundException();

        $chat->load('messages');

        return $chat->messages;
    }

    public function sendChatMessage(Chat $chat, $data)
    {
        if (!ChatService::chatExists($this, $chat))
            throw new ChatNotFoundException();

        $message = ChatMessageService::storeMessage($chat, $this, $data);

        $this->sendChatMessageEvent($message);

        return $message;
    }

    public function sendChatMessageEvent(Message $message)
    {
        event(new SendChatMessageEvent($message));
    }

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