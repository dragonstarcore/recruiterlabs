<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newuser = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'role_type' => '1',
            'status' => '1',
            'password' => Hash::make('123456')
        ]);

        $role = Role::where('id',$newuser->role_type)->first();
        $newuser->assignRole([$role->id]);
    }
}
