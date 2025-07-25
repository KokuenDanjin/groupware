<?php

namespace App\Repositories\Interfaces;

use App\Data\TopicData;
use App\Models\Topic;

interface TopicRepositoryInterface
{
    public function getAll(); // 投稿一覧を取得

    public function findOrFail(string $id); // 投稿を取得

    public function findByIdWithUser(string $id); // 投稿をユーザー情報と共に取得

    public function create(TopicData $data); // 投稿を登録

    public function update(string $id, TopicData $data); // 投稿を更新

    public function delete(Topic $topic);
    
}
