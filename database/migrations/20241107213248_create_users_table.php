<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{

    public function change()
    {
        $table = $this->table('users');
        $table->addColumn('first_name', 'string')
            ->addColumn('last_name', 'string')
            ->addColumn('middle_name', 'string', ['null' => true])
            ->addColumn('email', 'string')
            ->addColumn('password', 'string')
            ->addTimestamps()
            ->addIndex(['email'], ['unique' => true])
            ->create();
    }
}
