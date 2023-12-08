<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class acc_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $newSuper= User::create([
            'email' => 'superadmin@mail.com',
            'username' => 'Superadmin1',
            'password' => Hash::make('passwordpassword'),          
        ]);
        $newSuper->assignRole('superadmin');

        $newAdmin= User::create([
            'email' => 'admin@mail.com',
            'username' => 'Admin1',
            'password' => Hash::make('passwordpassword'),
        ]);
        $newAdmin->assignRole('admin');

        $newAttendant= User::create([
            'email' => 'attendant@mail.com',
            'username' => 'Attendant1',
            'password' => Hash::make('passwordpassword'),
        ]);
        $newAttendant->assignRole('attendant');
    }
}
