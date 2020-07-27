<?php

use App\Demand;
use Illuminate\Database\Seeder;

class DemandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Demand::class, 200)->create();
    }
}
