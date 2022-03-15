<?php

namespace Mojtaba\Chatable\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chatable_chats';

    protected $guarded = ['id'];

    public function sender()
    {
        return $this->morphTo();
    }

    public function receiver()
    {
        return $this->morphTo();
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'chatable');
    }

    public function message()
    {
        return $this->morphMany(Message::class, 'chatable')->orderBy('id', 'DESC')->limit(1);
    }

    public function scopeWithUnreadMessagesCount($q, Model $user, $fieldName = 'unread_messages_count')
    {
        return $q->withCount(["messages as $fieldName" => function ($q) use ($user) {
            $q->where(function ($q) use ($user) {
                $q->where(function ($q) use ($user) {
                    $q->where('messages.sender_id', '!=', $user->id)
                        ->orWhere('messages.sender_type', '!=', get_class($user));
                })
                    ->whereNull('messages.readed_at');
            });
        }]);
    }
}