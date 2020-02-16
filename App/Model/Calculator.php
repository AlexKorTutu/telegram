<?php
declare(strict_types=1);

namespace App\Model;

class Calculator
{
    public function calculateDebts($sessionId): string
    {
        $hiText = "Эй, юзеры! \n";
        $debtText = '';
        $debtsData = (new DebtTable())->getAggregatedDebts($sessionId);
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
            $debtText = "Поздравляю! У вас нет долгов в текущей сессии";
        }
        return $hiText . $debtText;
    }
}