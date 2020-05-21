<?php

use Phinx\Migration\AbstractMigration;

class Voting extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('motion');
        $table
            ->addColumn('type', 'string')
            ->addColumn('name', 'string')
            ->addColumn('start_datetime', 'datetime')
            ->addColumn('close_datetime', 'datetime')

            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
        ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->create();


        $table = $this->table('question');
        $table
            ->addColumn('text', 'string')
            ->addColumn('voting_system', 'string')
            ->addColumn('motion_id', 'integer')
            ->addForeignKey('motion_id', 'motion', 'id')
            ->create();


        $table = $this->table('choice');
        $table
            ->addColumn('text', 'string')
            ->addForeignKey('question_id', 'question', 'id')
            ->create();
    }
}
