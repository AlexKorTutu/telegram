<?php
declare(strict_types=1);

namespace App\Messages;

use Longman\TelegramBot\Entities\Message;

class MessageHelper
{
    private $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function getSender(): string
    {
        return "@{$this->message->getFrom()->getUsername()}";
    }

    public function getMentionedUsers()
    {
        $entities = $this->message->getEntities();
        $text = $this->message->getText(false);

        $mentions = [];
        foreach ($entities as $entity) {
            if ($entity->getType() === 'mention') {
                $offset = $entity->getOffset();
                $length = $entity->getLength();
                $mentions[] = substr($text, $offset, $length);
            }
        }
        //TODO:добавить проверку, что юзер есть в чате
        return $mentions;
    }
}