<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Model\SessionTable;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

/**
 * Start command
 */
class StartCommand extends UserCommand
{
    protected $name = 'start';
    protected $description = 'Start command';
    protected $usage = '/start';
    protected $version = '1.2.0';

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chat_id = $message->getChat()->getId();   // Get the current Chat ID

        $sessionId = (new SessionTable())->getLastActiveSessionByChatId($chat_id);

        $data = [                                  // Set up the new message data
            'chat_id' => $chat_id,                 // Set Chat ID to send the message to
            'text'    => 'Так, падажжи... Значит че бот умеет:' . PHP_EOL
                . '/debt - регистрация долгов, /mydebt и /calculate для подсчетов' . PHP_EOL
                . '/endsession чтобы закрыть текущую сессию подсчетов' . PHP_EOL
                . '/help для подробных описаний команд' . PHP_EOL
                . 'Текущая сессия в базе: ' . $sessionId  . PHP_EOL
                .  'вот и все что тебе нужно' , // Set message to send
        ];

        return Request::sendMessage($data);        // Send message!
    }
}