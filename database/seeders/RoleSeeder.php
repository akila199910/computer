<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Role::create(['name' => 'super_admin']);
        Role::create(['name' => 'reception']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'technician']);
        Role::create(['name' => 'customer']);
    }
}
