<?php

use Phinx\Migration\AbstractMigration;

class Voting extends AbstractMigration
{
    public function change()
    {
        $table = $this->table(
            'voting_motion',
            ['id' => FALSE, 'primary_key' => 'id']
        );

        $table
            ->addColumn('id', 'string')
            ->addColumn('type', 'string')
            ->addColumn('name', 'string')
            ->addColumn(
                'proposed_motion_source',
                'string',
                ['comment' => 'The exact data source this voting motion came from.']
            )
            ->addColumn('start_datetime', 'datetime')
            ->addColumn('close_datetime', 'datetime')
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
        ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->create();

        $table = $this->table(
            'voting_question',
            ['id' => FALSE, 'primary_key' => 'id']
        );
        $table
            ->addColumn('id', 'string')
            ->addColumn('text', 'string')
            ->addColumn('voting_system', 'string')
            ->addColumn('motion_id', 'string')
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('motion_id', 'voting_motion', 'id')
            ->create();

        $table = $this->table(
            'voting_choice',
            ['id' => FALSE, 'primary_key' => 'id']
        );
        $table
            ->addColumn('id', 'string')
            ->addColumn('text', 'string')
            ->addColumn('question_id', 'string')
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('question_id', 'voting_question', 'id')
            ->create();
    }
}
