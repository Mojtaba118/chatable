<?php

namespace Mojtaba\Chatable\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Mojtaba\Chatable\Exceptions\ChatNotFoundException;
use Mojtaba\Chatable\Models\Chat;

class ChatService
{
    public static function chats(Model $user)
    {
        return Chat::query()->where(static::getChatableCondition($user));
    }

    public static function chatWith(Model $sender, Model $receiver)
    {
        return $sender->senderChats()->create([
            'uuid' => Str::uuid()->toString(),
            'receiver_id' => $receiver->id,
            'receiver_type' => get_class($receiver),
        ]);
    }

    public static function chatExists(Model $user, Chat $chat): bool
    {
        return !!static::chats($user)->where('id', $chat->id)->first();
    }

    public static function hasChatWith(Model $user, Model $receiver)
    {
        return !!static::chats($user)->where(static::getChatableCondition($receiver))->first();
    }

    private static function getChatableCondition(Model $user)
    {
        return static function ($q) use ($user) {
            $q->where(function ($q) use ($user) {
                $q->where('sender_id', $user->id)
                    ->where('sender_type', get_class($user));

            })->orWhere(function ($q) use ($user) {
                $q->where('receiver_id', $user->id)
                    ->where('receiver_type', get_class($user));
            });
        };
    }
}