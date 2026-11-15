<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddProjectTechnologyForeignKeys extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('project_technology');

        $table
            ->changeColumn('project_id', 'integer', [
                'signed' => false,
                'null' => false,
            ])
            ->changeColumn('technology_id', 'integer', [
                'signed' => false,
                'null' => false,
            ])
            ->addForeignKey('project_id', 'projects', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->addForeignKey('technology_id', 'technologies', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->update();
    }
}
