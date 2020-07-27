<?php

use App\Receive;
use Illuminate\Database\Seeder;

class ReceivesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Receive::class, 200)->create();
    }
}
