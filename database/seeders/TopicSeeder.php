<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // すでに存在するユーザーからランダムに投稿を作る
        $users = User::all();

        foreach ($users as $user) {
            Topic::factory()->count(3)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
