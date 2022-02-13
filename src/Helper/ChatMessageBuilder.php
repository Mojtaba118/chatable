<?php

namespace Mojtaba\Chatable\Helper;

use Illuminate\Support\Str;
use Mojtaba\Chatable\Models\Chat;
use Mojtaba\Chatable\Models\Message;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ChatMessageBuilder
{
    private Chat $chat;
    private Model $sender;
    private Message $replyMessage;
    private UploadedFile $file;
    private string $caption;
    private string $message;

    public function setChat(Chat $chat): self
    {
        $this->chat = $chat;

        return $this;
    }

    public function setSender(Model $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @param UploadedFile $file
     * @param string|null $caption
     *
     * @return static
     */
    public function setFile(UploadedFile $file, string $caption = null): self
    {
        $this->file = $file;
        $this->caption = $caption;

        return $this;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function setReplyTo(Message $replyMessage): self
    {
        $this->replyMessage = $replyMessage;

        return $this;
    }

    public function store(): Message
    {
        $messageData = [
            'uuid' => Str::uuid()->toString()
        ];

        if ($this->sender) {
            $messageData['sender_id'] = $this->sender->id;
            $messageData['sender_type'] = get_class($this->sender);
        }

        if ($this->message) {
            $messageData['message'] = $this->message;
        }

        if ($this->replyMessage) {
            $messageData['reply_id'] = $this->replyMessage->id;
        }

        return $this->chat->messages()->create($messageData);
    }


}