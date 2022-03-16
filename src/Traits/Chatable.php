<?php

namespace Mojtaba\Chatable\Traits;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Mojtaba\Chatable\Events\SendChatMessageEvent;
use Mojtaba\Chatable\Exceptions\ChatNotFoundException;
use Mojtaba\Chatable\Models\Chat;
use Mojtaba\Chatable\Models\Group;
use Mojtaba\Chatable\Models\Media;
use Mojtaba\Chatable\Models\Member;
use Mojtaba\Chatable\Models\Message;
use Mojtaba\Chatable\Models\Readables;
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

    public function chatMedias(Chat $chat)
    {
        return $this->hasManyThrough(Media::class, Message::class, 'sender_id')
            ->where([
                'messages.sender_type' => static::class,
                'messages.chatable_id' => $chat->id,
                'messages.chatable_type' => get_class($chat)
            ]);
    }

    public function hasChat(Chat $chat)
    {
        return ChatService::chatExists($this, $chat);
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
        if (!$this->hasChat($chat))
            throw new ChatNotFoundException();

        $chat->load('messages');

        return $chat->messages;
    }

    public function sendChatMessage(Chat $chat, $data): Message
    {
        if (!$this->hasChat($chat))
            throw new ChatNotFoundException();

        $message = ChatMessageService::storeMessage($chat, $this, $data);

        $this->sendChatMessageEvent($message);

        return $message;
    }

    public function sendChatMessageEvent(Message $message)
    {
        event(new SendChatMessageEvent($message));
    }

    public function markChatMessageAsRead(Chat $chat)
    {
        if (!$this->hasChat($chat))
            throw new ChatNotFoundException();

        return Message::query()->where(function ($q) use ($chat) {
            $q->where('chatable_id', $chat->id)
                ->where('chatable_type', get_class($chat));
        })->where(function ($q) {
            $q->where('sender_id', '!=', $this->id)
                ->orWhere('sender_type', '!=', static::class);
        })->whereNull('readed_at')
            ->update([
                'readed_at' => now()
            ]);
    }

    public function members()
    {
        return $this->morphMany(Member::class, 'member');
    }

    public function readables()
    {
        return $this->morphMany(Readables::class, 'member');
    }


}