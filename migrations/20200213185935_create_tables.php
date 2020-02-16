<?php

use Phinx\Migration\AbstractMigration;

class CreateTables extends AbstractMigration
{
    public function up()
    {
        $sessionTable = $this->table('session');
        $sessionTable->addColumn('is_active', 'boolean', ['default' => 1])
            ->addColumn('chat_id', 'string')
            ->addIndex(['chat_id']);
        $sessionTable->save();

        $paymentTable = $this->table('payment');
        $paymentTable->addColumn('sum', 'decimal', ['scale' => 2, 'null' => false])
            ->addColumn('session_id', 'integer')
            ->addColumn('description', 'string', ['default' => ''])
            ->addColumn('is_open', 'boolean', ['default' => 1])
            ->addIndex(['session_id']);
        $paymentTable->save();

        $debtTable = $this->table('debt');
        $debtTable->addColumn('payment_id', 'integer')
            ->addColumn('description', 'string', ['default' => ''])
            ->addColumn('amount', 'decimal', ['scale' => 2, 'null' => false])
            ->addColumn('user_debtor', 'string', ['null' => false])
            ->addColumn('user_creditor', 'string', ['null' => false])
            ->addColumn('is_open', 'boolean', ['default' => 1])
            ->addIndex(['payment_id']);
        $debtTable->save();
    }

    public function down()
    {
        $this->table('debt')->drop()->save();
        $this->table('payment')->drop()->save();
        $this->table('session')->drop()->save();
    }
}
