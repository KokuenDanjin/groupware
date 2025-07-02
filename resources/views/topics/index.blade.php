<x-app-layout>
  <h1>掲示板</h1>

  <a href="{{ route('topics.create') }}" class="new-post-button">＋ 新規投稿</a>

  <div class="topic-list">
    @foreach ($topics as $topic)
      <div class="topic-item">
        <div class="topic-title">{{ $topic->title }}</div>
        <div class="topic-body">{{ Str::limit($topic->body, 50) }}</div>
        <div class="topic-meta">
          投稿者: {{ $topic->user->name }} ／ 投稿日: {{ $topic->created_at->format('Y-m-d') }}
        </div>
        <a href="{{ route('topics.show', $topic->id) }}" class="read-more">続きを読む</a>
      </div>
    @endforeach
  </div>
</x-app-layout>
