<?php

namespace App\Repositories;

use App\Data\TopicData;
use App\Models\Topic;
use App\Repositories\Interfaces\TopicRepositoryInterface;

class TopicRepository implements TopicRepositoryInterface
{
    public function getAll()
    {
        return Topic::with('user')->latest()->get();
    }

    public function create(TopicData $data) {
        return Topic::create([
            'title'   => $data->title,
            'body'    => $data->body,
            'user_id' => $data->userId,
        ]);
    }

    public function findByIdWithUser(string $id): Topic
    {
        return Topic::with('user')->findOrFail($id);
    }
}
