<?php

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
        if(\App\User::where('email', 'michel.bolzon123@gmail.com')->count() === 0){
            factory(\App\User::class, 1)->create([
                'name' => 'Michel',
                'email' => 'michel.bolzon123@gmail.com',
                'password' => bcrypt('123456')
            ]);
        }

    }
}
