<?php

namespace App\Repositories;

use App\Models\Topic;
use App\Repositories\Interfaces\TopicRepositoryInterface;

class TopicRepository implements TopicRepositoryInterface
{
    public function getAll()
    {
        return Topic::with('user')->latest()->get();
    }
}
