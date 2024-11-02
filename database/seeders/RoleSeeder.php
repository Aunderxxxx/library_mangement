<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['id' => 1, 'name' => 'Librarian'],
            ['id' => 2, 'name' => 'Member'],
        ];

        foreach($datas as $item){
            $role = new Role();
            $role->id = $item['id'];
            $role->name = $item['name'];
            $role->save();
        }
    }
}
