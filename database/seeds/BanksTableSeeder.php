<?php

use Illuminate\Database\Seeder;
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
        $data = [
            ['bank_name' => 'Itaú', 'bank_agency' => '9279', 'bank_account' => '10894-9'],
            ['bank_name' => 'Bradesco', 'bank_agency' => '0085', 'bank_account' => '232450-4'],
        ];

        DB::table('banks')->insert($data);
    }
}
