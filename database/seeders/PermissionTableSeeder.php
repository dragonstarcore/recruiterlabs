<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'client-list',
            'client-create',
            'client-edit',
            'client-delete',
            'ticket-list',
            'ticket-create',
            'ticket-edit',
            'ticket-delete',
            'people-list',
            'people-create',
            'people-edit',
            'people-delete',
            'people-view',
            'knowledgebase-list',
            'knowledgebase-create',
            'knowledgebase-edit',
            'knowledgebase-delete',
            'knowledgebase-view',
            'calendar-list',
            'calendar-create',
            'calendar-edit',
            'calendar-delete',
            
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
