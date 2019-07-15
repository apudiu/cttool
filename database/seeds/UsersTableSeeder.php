<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // creating single user
        User::create([
            'name' => 'Mohammad Ali Apu',
            'email' => 'apu@devnetlimited.com',
            'password' => bcrypt('123456789'),
            'created_at' => formatDateTimeSQL(),
            'updated_at' => formatDateTimeSQL()
        ]);
    }
}
