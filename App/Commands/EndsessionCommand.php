<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Model\Calculator;
use App\Model\SessionTable;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

/**
 * Start command
 */
class EndsessionCommand extends UserCommand
{
    protected $name = 'endsession';
    protected $description = 'Start command';
    protected $usage = '/endsession';
    protected $version = '1.0.0';

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chat_id = $message->getChat()->getId();   // Get the current Chat ID

        $sessionTable = new SessionTable();
        $sessionId = $sessionTable->getLastActiveSessionByChatId($chat_id);


        try {
            $reply = 'Закрыли сессию ' . $sessionId . '. Начать новую можно с помощью /start';
            $text = (new Calculator())->calculateDebts($sessionId);
            $sessionTable->closeSession($sessionId, $chat_id);
        } catch (\Exception $e) {
            $reply = 'Что-то пошло не так: ' . $e->getMessage();
        }


        $data = [                                  // Set up the new message data
            'chat_id' => $chat_id,                 // Set Chat ID to send the message to
            'text'    => $reply . PHP_EOL . $text
        ];

        return Request::sendMessage($data);        // Send message!
    }
}