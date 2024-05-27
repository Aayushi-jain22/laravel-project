<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin= new Admin();
        $admin->email= "admin@12gmail.com";
        $admin->password = bcrypt('aayu_22');
        $admin->save();
      
    }
}
