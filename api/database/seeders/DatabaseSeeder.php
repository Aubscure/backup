<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\Product::factory(10)->create();

        User::create([
            'name' => 'Aubrey Amante',
            'email' => 'aubreyamante@gmail.com',
            'password' => Hash::make('password'),
            'photo' => 'https://scontent.fdvo2-2.fna.fbcdn.net/v/t39.30808-1/445235033_2617333565112013_5207995003726842932_n.jpg?stp=dst-jpg_s480x480&_nc_cat=100&ccb=1-7&_nc_sid=5f2048&_nc_eui2=AeERNV1R91Hzw7xBtma0HpcA6AGWIMOSP4roAZYgw5I_iuBI9Znk83YiI2lXQQ2bXu_TGQvq__rQYSjGBGg8d7Fd&_nc_ohc=1ucIQbRIDIMQ7kNvgHdg3xU&_nc_ht=scontent.fdvo2-2.fna&oh=00_AYD79pKIQMy7S7pqro3d3cDN0kCLzww9_NSHb4OtYEHyjg&oe=6664AF16',
        ]);
    }
}
