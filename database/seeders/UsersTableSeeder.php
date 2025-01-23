<?php

namespace Database\Seeders;

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
        if(User::where('email', 'master@master.com')->count() === 0){
            User::factory()->count(1)->create([
                'name' => 'Michel',
                'email' => 'master@master.com',
                'password' => bcrypt('master')
            ]);
        }

    }
}
