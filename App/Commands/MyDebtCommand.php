<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Model\DebtTable;
use App\Model\SessionTable;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class MyDebtCommand extends UserCommand
{
    protected $name = 'my_debt';                                 // Your command's name
    protected $description = 'get all debts for asking user'; // Your command description
    protected $usage = '/mydebt';                               // Usage of your command
    protected $version = '1.0.0';                             // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chatId = $message->getChat()->getId();   // Get the current Chat ID

        // тут костыль - добавляем @ к имени должника, т.к. имя кредитора тоже начинается с @
        // (нужны одинаковые форматы для имен должника и кредитора, чтобы потом можно было матчить и вычитать долги)
        $user = "@{$message->getFrom()->getUsername()}";

        $debtText = "Эй, {$user}! \n";
        try {
            $sessionId = (new SessionTable())->getLastActiveSessionByChatId($chatId);
        } catch (\Throwable $e) {
            $sessionId = 1; // TODO как надо обработать ошибку?
        }        $debtsData = (new DebtTable())->getActiveDebtsForUser($user, $sessionId);

        foreach ($debtsData as $debt) {
            $debtText .= "Ты должен {$debt['user_creditor']} {$debt['amount']} за \"{$debt['description']}\".\n";
        }

        $data = [                                  // Set up the new message data
            'chat_id' => $chatId,                  // Set Chat ID to send the message to
            'text'    => $debtText                 // Set message to send
        ];


        return Request::sendMessage($data);        // Send message!
    }
}
