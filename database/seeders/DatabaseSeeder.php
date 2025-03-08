<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 創建角色
        \App\Models\Role::create([
            'name' => '管理員',
            'slug' => 'admin'
        ]);
        \App\Models\Role::create([
            'name' => '員工',
            'slug' => 'employee'
        ]);
        \App\Models\Role::create([
            'name' => '客戶',
            'slug' => 'customer'
        ]);

        // 創建管理員
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'admin123',
            'role_id' => 1,
        ]);

        // 創建員工
        \App\Models\User::create([
            'name' => 'Employee',
            'email' => 'employee@example.com',
            'password' => 'employee123',
            'role_id' => 2,
        ]);

        // 創建洗衣類型
        $laundryTypes = [
            [
                'name' => '一般衣物',
                'price' => 100,
                'description' => '適合一般衣物清洗'
            ],
            [
                'name' => '精緻衣物',
                'price' => 150,
                'description' => '適合精緻衣物清洗'
            ],
            [
                'name' => '大型衣物',
                'price' => 200,
                'description' => '適合大型衣物如床單、棉被等'
            ],
            [
                'name' => '急速服務',
                'price' => 300,
                'description' => '24小時內完成的急速服務'
            ],
            [
                'name' => '特殊污漬處理',
                'price' => 250,
                'description' => '適合需要特殊處理的污漬'
            ],
        ];

        foreach ($laundryTypes as $type) {
            \App\Models\LaundryType::create($type);
        }

        // 創建測試客戶
        $customers = [
            [
                'name' => '張小明',
                'email' => 'ming@example.com',
                'phone' => '0912345678',
            ],
            [
                'name' => '李大華',
                'email' => 'hua@example.com',
                'phone' => '0923456789',
            ],
            [
                'name' => '王美麗',
                'email' => 'mei@example.com',
                'phone' => '0934567890',
            ],
            [
                'name' => '陳志明',
                'email' => 'zhi@example.com',
                'phone' => '0945678901',
            ],
            [
                'name' => '林小芳',
                'email' => 'fang@example.com',
                'phone' => '0956789012',
            ],
        ];

        foreach ($customers as $customer) {
            \App\Models\User::create([
                'name' => $customer['name'],
                'email' => $customer['email'],
                'password' => 'customer123',
                'phone' => $customer['phone'],
                'role_id' => 3, // 客戶角色
            ]);
        }
    }
}
