<?php

use App\Award;
use Illuminate\Database\Seeder;

class AwardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Award::class, 200)->create();
    }
}
