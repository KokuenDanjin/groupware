@vite(['resources/js/pages/topics.js'])

<x-app-layout>
  <div class="form-container">
    <h1 class="form-container__title">📄 投稿の詳細</h1>

    <div class="topic-detail">
      <div class="topic-detail__header">
        <h2 class="topic-detail__title">{{ $topic->title }}</h2>
        <p class="topic-detail__user">👤 投稿者：{{ $topic->user->name }}</p>
      </div>

      <div class="topic-detail__body">
        {!! nl2br(e($topic->body)) !!}
      </div>

      <div class="topic-detail__actions">
        @if (auth()->check() && auth()->id() === $topic->user_id)
            <a href="{{ route('topics.edit', $topic->id) }}" class="topic-detail__edit">編集</a>
        @endif
        <a href="{{ route('topics.index') }}" class="topic-detail__back">一覧に戻る</a>
      </div>
    </div>
  </div>
</x-app-layout>
