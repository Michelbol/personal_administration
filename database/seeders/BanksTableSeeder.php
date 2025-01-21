<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;
use Illuminate\Support\Facades\DB;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Bank::where('id', '>', 0)->count() === 0) {
            DB::unprepared(file_get_contents(base_path('database/seeds/sql/BanksSeeder/insert_default_banks.sql')));
        }
    }
}
