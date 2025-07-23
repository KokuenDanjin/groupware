<?php

namespace App\Repositories\Interfaces;

use App\Data\TopicData;

interface TopicRepositoryInterface
{
    public function getAll(); // 投稿一覧を取得

    public function create(TopicData $data); // 投稿を登録
}
