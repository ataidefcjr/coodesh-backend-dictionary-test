<?php

namespace Database\Seeders;

use App\Console\Commands\ImportWords;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory(1)->create(
        //         [
        //         'name' => 'Ademir',
        //         'email' => 'ademir@teste.com',
        //         'password' => '123456'
        //     ]
        // );

        Artisan::call('import:words');
    }
}
