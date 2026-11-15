<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTechnologiesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('technologies');

        $table
            ->addColumn('name', 'string', [
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('slug', 'string', [
                'limit' => 120,
                'null' => false,
            ])
            ->addColumn('created_at', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->addColumn('updated_at', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->addIndex(['slug'], [
                'unique' => true,
            ])
            ->create();
    }
}
