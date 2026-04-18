<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(\Escalated\Laravel\Database\Seeders\PermissionSeeder::class);
        $this->call(DemoSeeder::class);
    }
}
