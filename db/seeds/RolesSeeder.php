<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

final class RolesSeeder extends AbstractSeed
{
    public function run(): void
    {
        $table = $this->table('roles');
        $table->insert([
            ['name' => 'Administrator', 'slug' => 'administrator', 'description' => 'Full access to portfolio administration.'],
            ['name' => 'Editor', 'slug' => 'editor', 'description' => 'Can manage portfolio content.'],
        ])->saveData();
    }
}
