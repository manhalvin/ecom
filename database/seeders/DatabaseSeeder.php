<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $groupId = DB::table('groups')->insertGetId([
            'name' => 'Admin',
            'user_id' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        if ($groupId > 0) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'Quốc Mạnh',
                'email' => 'quocmanh1998s@gmail.com',
                'password' => Hash::make('123456789'),
                'group_id' => $groupId,
                'user_id' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($userId > 0) {
            for ($i=0; $i <= 10; $i++) {
                DB::table('posts')->insert([
                    'title' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                    'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                    'user_id' => $userId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        DB::table('roles')->insert([
            ['name' => 'admin', 'display_name' => 'Quản trị hệ thống'],
            ['name' => 'guest', 'display_name' => 'Khách hàng'],
            ['name' => 'dev', 'display_name' => 'Phát triển hệ thống'],
            ['name' => 'content', 'display_name' => 'Chỉnh sửa nội dung'],
        ]);

        DB::table('modules')->insertGetId([
            'title' => 'users',
            'content' => 'Quản lý người dùng',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('modules')->insertGetId([
            'title' => 'groups',
            'content' => 'Quản lý nhóm',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
