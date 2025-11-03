<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'first_name'        => 'Admin',
            'last_name'         => 'SalbaKitaPH',
            'email'             => 'admin@salbakitaph.gov',
            'password'          => Hash::make('password'),
            'phone_number'      => '09171234567',
            'role'              => 'admin',
            'is_verified'       => true,
            'reputation_score'  => 1000
        ]);

        // Official
        User::create([
            'first_name'        => 'Juan',
            'last_name'         => 'Dela Cruz',
            'email'             => 'official@salbakitaph.gov',
            'password'          => Hash::make('password'),
            'phone_number'      => '09181234567',
            'role'              => 'official',
            'is_verified'       => true,
            'reputation_score'  => 500
        ]);

        // Volunteers
        User::create([
            'first_name'        => 'Maria',
            'last_name'         => 'Santos',
            'email'             => 'volunteer1@example.com',
            'password'          => Hash::make('password'),
            'phone_number'      => '09191234567',
            'role'              => 'volunteer',
            'is_verified'       => true,
            'reputation_score'  => 250
        ]);
        // Additional volunteer
        User::create([
            'first_name'        => 'Pedro',
            'last_name'         => 'Reyes',
            'email'             => 'volunteer2@example.com',
            'password'          => Hash::make('password'),
            'phone_number'      => '09201234567',
            'role'              => 'volunteer',
            'is_verified'       => true,
            'is_active'         => true,
            'reputation_score'  => 200
        ]);

        // Regular Users
        User::factory(5)->create();
    }
}
