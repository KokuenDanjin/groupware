<?php

namespace app\Data;

use \App\Http\Requests\TopicStoreRequest;

class TopicData
{
    public string $title;
    public string $body;
    public int $userId;

    public function __construct(string $title, string $body, int $userId)
    {
        $this->title = $title;
        $this->body = $body;
        $this->userId = $userId;
    }

    public static function fromRequest(TopicStoreRequest $request): self
    {
        return new self(
        $request->input('title'),
        $request->input('body'),
        $request->user()->id
        );
    }
}
