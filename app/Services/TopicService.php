<?php

namespace App\Services;

use App\Data\TopicData;
use App\Models\Topic;
use App\Repositories\Interfaces\TopicRepositoryInterface;
use Illuminate\Support\Facades\DB;

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

    public function getTopicById(string $id): Topic
    {
        return $this->topicRepository->findOrFail($id);
    }

    public function createTopic(TopicData $data): Topic
    {
        return DB::transaction(function () use ($data) {
            return $this->topicRepository->create($data);
        });
    }

    public function updateTopic(string $id, TopicData $data): Topic
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->topicRepository->update($id, $data);
        });
    }

    public function getTopicByIdWithUser(string $id): Topic
    {
        return $this->topicRepository->findByIdWithUser($id);
    }

    public function deleteTopic(Topic $topic): void
    {
        DB::transaction(function () use ($topic) {
            $this->topicRepository->delete($topic);
        });
    }
}
