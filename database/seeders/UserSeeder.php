<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'uid'=> '9355AF2A',
            'nama'=> 'Mas Amba',
            'saldo' => 69696969
        ]);
        User::create([
            'uid'=> '44F62C3',
            'nama'=> 'Mas Rusdi',
            'saldo' => 1234552
        ]);
        User::create([
            'uid'=> '1234567',
            'nama'=> 'Mas Atmin',
            'saldo' => 898989899
        ]);
    }
}
