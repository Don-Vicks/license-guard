<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = User::factory()->create([
            'name' => 'Sudo User',
            'email' => 'sudo@gmail.com',
        ]);
        $user->assignRole('admin');

        $user = User::factory()->create([
            'name' => 'Normal User',
            'email' => 'normal@gmail.com',
        ]);
        $user->assignRole('user');
    }
}
