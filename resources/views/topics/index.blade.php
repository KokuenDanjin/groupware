@vite(['resources/js/pages/topics.js'])
<x-app-layout>
  <div class="board">
    <div class="board__header">
      <h1 class="board__title">📋 掲示板</h1>
      <a href="{{ route('topics.create') }}" class="board__new-post-button">＋ 新規投稿</a>
    </div>

    @if (session('success'))
    <div class="alert alert--success">
        {{ session('success') }}
    </div>
    @endif

    <div class="board__list">
      @foreach ($topics as $topic)
        <div class="topic-card">
          <h2 class="topic-card__title">{{ $topic->title }}</h2>
          <p class="topic-card__body">{{ Str::limit($topic->body, 100) }}</p>
          <div class="topic-card__meta">
            投稿者: <span class="topic-card__user">{{ $topic->user->name }}</span> ／ 投稿日: {{ $topic->created_at->format('Y-m-d') }}
          </div>
          <a href="{{ route('topics.show', $topic->id) }}" class="topic-card__read-more">続きを読む →</a>
        </div>
      @endforeach
    </div>
  </div>
</x-app-layout>
