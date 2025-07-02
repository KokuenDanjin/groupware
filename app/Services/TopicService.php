<?php

namespace App\Services;

use App\Repositories\Interfaces\TopicRepositoryInterface;

class TopicService
{
    protected TopicRepositoryInterface $topicRepository;

    public function __construct(TopicRepositoryInterface $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    public function getAllTopics()
    {
        return $this->topicRepository->getAll();
    }
}
