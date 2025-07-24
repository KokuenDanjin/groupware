<?php

namespace App\Services;

use App\Data\TopicData;
use App\Models\Topic;
use App\Repositories\Interfaces\TopicRepositoryInterface;
use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Facades\DB as FacadesDB;

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

    public function createTopic(TopicData $data): Topic
    {
        return FacadesDB::transaction(function () use ($data) {
            return $this->topicRepository->create($data);
        });
    }

    public function getTopicByIdWithUser(string $id): Topic
    {
        return $this->topicRepository->findByIdWithUser($id);
    }
}
