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
            'user-list',
            'create-user',
            'update-user',
            'delete-user',
            'role-list',
            'create-role',
            'update-role',
            'delete-role',
            'setting',
            'update-setting',
            'privacy',
            'update-privacy',
            'term',
            'update-term',
            'about-us',
            'update-about-us',
            'question-list',
            'create-question',
            'update-question',
            'delete-question'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

}
