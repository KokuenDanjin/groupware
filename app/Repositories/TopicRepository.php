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

    public function findOrFail(string $id): Topic
    {
        return Topic::findOrFail($id);
    }

    public function findByIdWithUser(string $id): Topic
    {
        return Topic::with('user')->findOrFail($id);
    }

    public function create(TopicData $data) 
    {
        return Topic::create([
            'title'   => $data->title,
            'body'    => $data->body,
            'user_id' => $data->userId,
        ]);
    }

    public function update(string $id, TopicData $data)
    {
        $topic = $this->findOrFail($id); 
        $topic->update([
            'title'   => $data->title,
            'body'    => $data->body,
            'user_id' => $data->userId,
        ]);

        return $topic;
    }

    public function delete(Topic $topic): void
    {
        $topic->delete();
    }
}
