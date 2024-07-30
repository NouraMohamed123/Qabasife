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
            'product-list',
            'create-product',
            'update-product',
            'delete-product',
            'representative-list',
            'create-representative',
            'update-representative',
            'delete-representative',
            'order-list',
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
            'about-us',
            'update-about-us',
            'privacy',
            'update-privacy',
            'term',
            'update-term',

        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

}
