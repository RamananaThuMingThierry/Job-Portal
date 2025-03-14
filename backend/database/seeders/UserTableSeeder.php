<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('users')->insert([
        //     'slug' => Str::slug(Str::random(20)),
        //     'name' => "RAMANANA Thu Ming Thierry",
        //     'email' => "ramananathumingthierry@gmail.com",
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'is_admin' => true,
        //     'status' => 'active',
        //     'image' => 'default.png',
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);
    
        User::factory(2)->create();
    }

}
