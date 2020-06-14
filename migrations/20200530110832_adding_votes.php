<?php

use Phinx\Migration\AbstractMigration;

class AddingVotes extends AbstractMigration
{

    public function change()
    {
        // Voting motion
        $table = $this->table('voting_motion');
        // Index for speed
        $table->addIndex(
            ['proposed_motion_source'],
            [
                'unique' => true,
                'name' => 'voting_motion_unique'
            ]
        );
        $table->addIndex(
            ['state'],
            [
                'name' => 'state_index'
            ]
        );
        $table->save();


        // Voting question
        $table = $this->table('voting_question');
        // Index for speed
        $table->addIndex(
            ['motion_id'],
            [
                'name' => 'motion_id_index'
            ]
        );
        // Unique to prevent duplicates
        $table->addIndex(
            ['motion_id', 'text'],
            [
                'unique' => true,
                'name' => 'voting_question_unique'
            ]
        );
        $table->save();


        // Voting choice indexes
        $table = $this->table('voting_choice');
        // Index for speed
        $table->addIndex(
            ['question_id'],
            [
                'name' => 'question_id_index'
            ]
        );

        // Unique to prevent duplicates
        $table->addIndex(
            ['question_id', 'text'],
            [
                'unique' => true,
                'name' => 'voting_choice_unique'
            ]
        );
        $table->save();

        // Vote record
        $table = $this->table(
            'vote_record',
            ['id' => FALSE, 'primary_key' => 'id']
        );
        $table
            ->addColumn('id', 'string')
            ->addColumn('user_id', 'string')
            ->addColumn('choice_id', 'string')
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
//            ->addIndex(['question_id'],['name' => 'vote_record_question_id_index'])
            ->addIndex(['choice_id'],['name' => 'vote_record_choice_id_index'])

//            ->addForeignKey('question_id', 'voting_question', 'id')
            ->addForeignKey('choice_id', 'voting_choice', 'id')
            ->create();
    }
}
