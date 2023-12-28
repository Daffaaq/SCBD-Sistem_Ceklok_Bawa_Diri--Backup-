<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'pegawai'],
            ['name' => 'kasubagumum'],
            // Add more roles as needed
        ]);

        // Get role IDs
        $adminRoleId = DB::table('roles')->where('name', 'admin')->first()->id;
        $pegawaiRoleId = DB::table('roles')->where('name', 'pegawai')->first()->id;
        $kasubagumumRoleId = DB::table('roles')->where('name', 'kasubagumum')->first()->id;

        DB::table('users')->insert([
            [
                'uuid' => Str::uuid(),
                'name' => 'Admin User',
                'email' => 'daffaaqila48@gmail.com',
                'no_telp' => 1234567890,
                'jabatan' => 'Admin',
                'role_id' => $adminRoleId,
                'password' => bcrypt('password'), // Replace 'password' with the actual password
                'remember_token' => Str::random(10),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Pegawai User',
                'email' => 'pegawai@gmail.com',
                'no_telp' => 1234567891,
                'jabatan' => 'Pegawai',
                'role_id' => $pegawaiRoleId,
                'password' => bcrypt('password'), // Replace 'password' with the actual password
                'remember_token' => Str::random(10),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Kasubagumum User',
                'email' => 'kasubagumum@gamil.com',
                'no_telp' => 1234567892,
                'jabatan' => 'Kasubagumum',
                'role_id' => $kasubagumumRoleId,
                'password' => bcrypt('password'), // Replace 'password' with the actual password
                'remember_token' => Str::random(10),
            ],
            // Add more users as needed
        ]);
    }
}
