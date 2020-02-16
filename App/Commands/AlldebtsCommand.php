<?php


namespace Longman\TelegramBot\Commands\UserCommands;

use App\Model\DebtTable;
use App\Model\SessionTable;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class AlldebtsCommand extends UserCommand
{
    protected $name = 'alldebts';                                 // Your command's name
    protected $description = 'get all debts for session';          // Your command description
    protected $usage = '/alldebts';                               // Usage of your command
    protected $version = '1.0.0';                                  // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chatId = $message->getChat()->getId();   // Get the current Chat ID

        $message->getChat()->getUsername();
        $sessionId = (new SessionTable())->getLastActiveSessionByChatId($chatId);

        $data = [                                  // Set up the new message data
            'chat_id' => $chatId,                  // Set Chat ID to send the message to
            'text'    => $this->prepareRawDebtsText($sessionId)                 // Set message to send
        ];


        return Request::sendMessage($data);        // Send message!
    }

    /*
     *  Все долги по текущей сессии без аггрегации, но с комментариями
     */
    private function prepareRawDebtsText(int $session)
    {
        $headerText = "Таблица долгов на текущий момент: \n";
        $debtText = '';
        $debtsData = $this->getDebtTable()->getAllActiveDebts($session);
        foreach ($debtsData as $debt) {
            if (isset($debt['user_debtor']) && isset($debt['user_creditor'])
                && isset($debt['amount']) && isset($debt['description'])) {
                $debtText .= "{$debt['user_debtor']} должен {$debt['user_creditor']} {$debt['amount']} за \"{$debt['description']}\".\n";
            }
        }
        if (empty($debtText)) {
            $debtText =  "Поздравляю! У вас нет долгов в текущей сессии";
        }
        return $headerText . $debtText;
    }

    private function getDebtTable()
    {
        return new DebtTable();
    }
}