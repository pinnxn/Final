<?php

namespace Database\Seeders;

use App\Models\Register;
use Illuminate\Database\Seeder;

class RegisterSeeder extends Seeder
{
    public function run(): void
    {
        Register::factory()->count(40)->create();
    }
}
