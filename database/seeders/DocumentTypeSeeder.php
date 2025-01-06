<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['title' => 'General', 'type' => 'General','status' => '1'],
            ['title' => 'Terms', 'type' => 'Terms','status' => '1'],
            ['title' => 'Marketing', 'type' => 'Marketing','status' => '1'],
            ['title' => 'Marketing & brand', 'type' => 'Marketing & brand','status' => '1'],
            ['title' => 'Legal business documentation', 'type' => 'Legal business documentation','status' => '1'],
            ['title' => 'Templates', 'type' => 'Templates','status' => '1'],
            ['title' => 'HR document', 'type' => 'HR document','status' => '2'],
            ['title' => 'People document', 'type' => 'People document','status' => '2'],
        ];

        foreach($users as $user){
            DocumentType::create($user);
        }
    }
}
