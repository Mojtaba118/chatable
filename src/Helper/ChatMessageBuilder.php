<?php

namespace Mojtaba\Chatable\Helper;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mojtaba\Chatable\Models\Chat;
use Mojtaba\Chatable\Models\Media;
use Mojtaba\Chatable\Models\Message;

class ChatMessageBuilder
{
    private Chat $chat;
    private Model $sender;
    private ?Message $replyMessage = null;
    private ?UploadedFile $file = null;
    private string $messageContent;

    private Message $message;

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
     * @return static
     */
    public function setFile(UploadedFile $file): self
    {
        $this->file = $file;
        return $this;
    }

    public function setMessageContent(string $messageContent): self
    {
        $this->messageContent = $messageContent;

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

        if ($this->messageContent) {
            $messageData['message'] = $this->messageContent;
        }

        if ($this->replyMessage) {
            $messageData['reply_id'] = $this->replyMessage->id;
        }

        return DB::transaction(function () use ($messageData) {
            $this->message = $this->chat->messages()->create($messageData);

            if ($this->file) {
                $this->storeFiles($this->message);
                $this->message->load('medias');
            }

            if ($this->replyMessage)
                $this->message->load('reply');

            return $this->message;
        });
    }

    private function storeFiles(Message $message): Media
    {
        $now = now();

        $filePath = "chat_medias/$now->year/$now->month/" . $this->chat->uuid;
        $fileName = $now->year . '_' . $now->month . '_' . $now->day . '_' . $this->file->hashName();

        Storage::drive('public')->putFileAs($filePath, $this->file, $fileName);

        return $message->medias()->create([
            'uuid' => Str::uuid()->toString(),
            'path' => "$filePath/$fileName",
            "type" => $this->file->getMimeType()
        ]);
    }


}