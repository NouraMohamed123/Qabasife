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

            "about-list",
           "update-about",
           "privacy-list",
          " update-privacy",
           "term-list",
           "update-term",
           "questions",
          "questions-update",
            "questions-delete",
            "questions-create",
            "users-list",
            "update-user",
            "delete-user",
            "add-user",
            "roles-list",
            "update-role",
            "delete-role",
            "add-role",
            "setting",




        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

}
