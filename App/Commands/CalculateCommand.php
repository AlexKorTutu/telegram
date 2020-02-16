<?php


namespace Longman\TelegramBot\Commands\UserCommands;

use App\Model\DebtTable;
use App\Model\SessionTable;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class CalculateCommand  extends UserCommand
{
    protected $name = 'calculate';                                 // Your command's name
    protected $description = 'calculate all debts for session'; // Your command description
    protected $usage = '/calculate';                               // Usage of your command
    protected $version = '1.0.0';                             // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chatId = $message->getChat()->getId();   // Get the current Chat ID

        try {
            $sessionId = (new SessionTable())->getLastActiveSessionByChatId($chatId);
        } catch (\Throwable $e) {
            $sessionId = 1; // TODO как надо обработать ошибку?
        }

        $text = $this->prepareDebtsText($sessionId);

        $data = [                                  // Set up the new message data
            'chat_id' => $chatId,                  // Set Chat ID to send the message to
            'text'    => $text                 // Set message to send
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


    /*
     * Все долги с аггрегацией по должнику и кредитору
     */
    private function prepareDebtsText(int $session)
    {
        $hiText = "Эй, юзеры! \n";
        $debtText = '';
        $debtsData = (new DebtTable())->getAggregatedDebts($session);
        foreach ($debtsData as $debt) {
            if (isset($debt['aggregated'])) {
                if ($debt['aggregated'] > 0) {
                    $debt_amount = $debt['aggregated'];
                } else {
                    continue;
                }
            } elseif (isset($debt['sum'])) {
                $debt_amount = $debt['sum'];
            }
            if (isset($debt['user_debtor']) && isset($debt['user_creditor']) && isset($debt_amount)) {
                $debtText .= "{$debt['user_debtor']} должен {$debt['user_creditor']} в сумме {$debt_amount}.\n";
            }
        }
        if (empty($debtText)) {
            $debtText =  "Поздравляю! У вас нет долгов в текущей сессии";
        }
        return $hiText . $debtText;
    }

    private function getDebtTable()
    {
        return new DebtTable();
    }
}