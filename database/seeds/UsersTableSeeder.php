<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Thiago',
                'email' => 'financeiro@relacionamais.com.br',
                'email_verified_at' => now(),
                'password' => bcrypt('47254231771678'),
                'remember_token' => Str::random(10),
                'role' => User::ROLE_ADMIN,
            ],
            [
                'name' => 'Operacional',
                'email' => 'operacional@relacionamais.com.br',
                'email_verified_at' => now(),
                'password' => bcrypt('40094907670267'),
                'remember_token' => Str::random(10),
                'role' => User::ROLE_ADMIN,
            ],
            [
                'name' => 'Comercial',
                'email' => 'comercial@relacionamais.com.br',
                'email_verified_at' => now(),
                'password' => bcrypt('20286955839510'),
                'remember_token' => Str::random(10),
                'role' => User::ROLE_ADMIN,
            ],
            [
                'name' => 'Cristiano',
                'email' => 'cto.cristiano@softgenese.com',
                'email_verified_at' => now(),
                'password' => bcrypt('crypto(aero\Quimic@1)'),
                'remember_token' => Str::random(10),
                'role' => User::ROLE_ADMIN,
            ],
            [
                'name' => 'Carlos',
                'email' => 'carlos@softgenese.com',
                'email_verified_at' => now(),
                'password' => bcrypt('$oft302718'),
                'remember_token' => Str::random(10),
                'role' => User::ROLE_ADMIN,
            ]
        ];
        DB::table('users')->insert($data);
    }
}
