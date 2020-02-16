<?php


namespace Longman\TelegramBot\Commands\UserCommands;

use App\Model\Calculator;
use App\Model\DebtTable;
use App\Model\SessionTable;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class CalculateCommand extends UserCommand
{
    protected $name = 'calculate';                                 // Your command's name
    protected $description = 'calculate all debts for session'; // Your command description
    protected $usage = '/calculate';                               // Usage of your command
    protected $version = '1.0.0';                             // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chatId = $message->getChat()->getId();   // Get the current Chat ID

        $sessionId = (new SessionTable())->getLastActiveSessionByChatId($chatId);

        $text = (new Calculator())->calculateDebts($sessionId);

        $data = [                                  // Set up the new message data
            'chat_id' => $chatId,                  // Set Chat ID to send the message to
            'text' => $text                 // Set message to send
        ];


        return Request::sendMessage($data);        // Send message!
    }

    /*
     * Все долги просуммированные по должнику и кредитору. Но пока без аггрегации
     */
//    private function prepareDebtsText(int $session): string
//    {
//        $hiText = "Эй, юзеры! \n";
//        $debtText = '';
//        $debtsData = $this->getDebtTable()->getAllDebtsSummed($session);
//        foreach ($debtsData as $debt) {
//            if (isset($debt['user_debtor']) && isset($debt['user_creditor']) && isset($debt['sum'])) {
//                $debtText .= "{$debt['user_debtor']} должен {$debt['user_creditor']} {$debt['sum']}.\n";
//            }
//        }
//        if (empty($debtText)) {
//            $debtText = "Поздравляю! У вас нет долгов в текущей сессии";
//        }
//        return $hiText . $debtText;
//    }
}