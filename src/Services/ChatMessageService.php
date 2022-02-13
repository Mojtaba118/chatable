<?php

namespace Mojtaba\Chatable\Services;

use Illuminate\Database\Eloquent\Model;
use Mojtaba\Chatable\Helper\ChatMessageBuilder;
use Mojtaba\Chatable\Models\Chat;
use Mojtaba\Chatable\Models\Message;

class ChatMessageService
{
    public static function storeMessage(Chat $chat, Model $sender, $data): Message
    {
        $messageBuilder = (new ChatMessageBuilder)->setChat($chat)
            ->setSender($sender);

        if (isset($data['file'])) {
            $messageBuilder->setFile($data['file'])
                ->setMessageContent($data['message'] ?? null);
        } else {
            $messageBuilder->setMessageContent($data['message']);
        }

        if (isset($data['reply_id'])) {
            $message = Message::where([
                'chatable_id' => $chat->id,
                'chatable_type' => get_class($chat),
            ])->findOrFail($data['reply_id']);

            $messageBuilder->setReplyTo($message);
        }

        return $messageBuilder->store();
    }
}