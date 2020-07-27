<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        // $this->call(ManagersTableSeeder::class);
        // $this->call(ClientsTableSeeder::class);
        // $this->call(ProvidersTableSeeder::class);
        $this->call(BanksTableSeeder::class);
        // $this->call(DemandsTableSeeder::class);
        // $this->call(NotesTableSeeder::class);
        // $this->call(ReceivesTableSeeder::class);
        // $this->call(BillsTableSeeder::class);
        // $this->call(AwardsTableSeeder::class);
    }
}
