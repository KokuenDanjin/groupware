<?php

namespace App\Http\Controllers;

use App\Data\TopicData;
use App\Http\Requests\TopicStoreRequest;
use App\Services\TopicService;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TopicService $service)
    {
        $topics = $service->getAllTopics();
        return view('topics.index', compact('topics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('topics.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TopicStoreRequest $request, TopicService $service)
    {
        $data = TopicData::fromRequest($request);

        $service->createTopic($data);

        return redirect()->route('topics.index')
            ->with('success', '投稿が完了しました！');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, TopicService $service)
    {
        $topic = $service->getTopicByIdWithUser($id);
        return view('topics.show', compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, TopicService $service)
    {
        $topic = $service->getTopicByIdWithUser($id);
        return view('topics.edit', compact('topic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TopicStoreRequest $request, string $id, TopicService $service)
    {
        $topic = $service->getTopicById($id);

        $this->authorize('update', $topic);

        $data = TopicData::fromRequest($request);
        $service->updateTopic($id, $data);

        return redirect()->route('topics.show', $id)
            ->with('success', '投稿を更新しました！');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, TopicService $service)
    {
        $topic = $service->getTopicById($id); 

        $this->authorize('delete', $topic); 

        $service->deleteTopic($topic);

        return redirect()->route('topics.index')
            ->with('success', '投稿を削除しました。');
    }
}
