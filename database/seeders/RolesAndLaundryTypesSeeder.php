<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesAndLaundryTypesSeeder extends Seeder
{
    public function run()
    {
        // 建立角色
        DB::table('roles')->insert([
            ['name' => '客戶', 'slug' => 'customer', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '員工', 'slug' => 'employee', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 建立洗衣類型
        DB::table('laundry_types')->insert([
            [
                'name' => '一般衣物',
                'description' => '適合日常衣物',
                'price' => 100,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '被單',
                'description' => '單人、雙人被單',
                'price' => 200,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '特殊衣物',
                'description' => '需要特殊處理的衣物',
                'price' => 300,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
