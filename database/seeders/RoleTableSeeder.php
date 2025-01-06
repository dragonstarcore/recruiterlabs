<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Admin',   
            'Client'
         ];

        $Admin_permission = array(
            'client-list',
            'client-create',
            'client-edit',
            'client-delete',
            'ticket-list',
            'ticket-create',
            'ticket-edit',
            'ticket-delete',
            'people-list',
            'people-view',
            'knowledgebase-list',
            'knowledgebase-view',
        );  

        $Client_permission = array(
            'client-edit',
            'ticket-list',
            'ticket-create',
            'ticket-edit',
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
        ); 

        foreach ($roles as $role) {
            $new_role = Role::create(['name' => $role]);
            $arr = $role.'_permission';            
            $permissions = Permission::whereIn('name',$$arr)->pluck('id','id')->toArray();
            $new_role->syncPermissions($permissions);
        }
    }
}
