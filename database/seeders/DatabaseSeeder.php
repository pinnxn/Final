<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'role_id' => '1',
            'password' => bcrypt('123456'),
        ]);

        User::create([
            'name' => 'prene',
            'email' => 'janyarat.ma@gmail.com',
            'role_id' => '2',
            'password' => bcrypt('123456'),
        ]);

        $this->call([
            RoleSeeder::class,
            CompanySeeder::class,
            RegisterSeeder::class,
            // ... other seeders ...
        ]);
    }
}
 
