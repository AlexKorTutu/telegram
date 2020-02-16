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
class HelpCommand extends UserCommand
{
    protected $name = 'help';
    protected $description = 'Start command';
    protected $usage = '/help';
    protected $version = '1.0.0';

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chat_id = $message->getChat()->getId();   // Get the current Chat ID

        $sessionId = (new SessionTable())->getLastActiveSessionByChatId($chat_id);

        $data = [                                  // Set up the new message data
            'chat_id' => $chat_id,                 // Set Chat ID to send the message to
            'text'    => 'Так, падажжи... Значит че бот умеет:' . PHP_EOL
                . '/debt @username сумма_без_копеек описание ### добавит в базу ваш долг юзернейму' . PHP_EOL
                . '/endsession чтобы закрыть текущую сессию подсчетов, перед этим неплохо бы сделать подсчеты' . PHP_EOL
                . '/mydebt ваш список долгов' . PHP_EOL
                . '/calculate список долгов, всех кто поучаствовал в текущей сессии' . PHP_EOL
        ];

        return Request::sendMessage($data);        // Send message!
    }
}