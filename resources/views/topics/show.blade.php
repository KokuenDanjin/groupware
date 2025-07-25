@vite(['resources/js/pages/topics.js'])

<x-app-layout>
    @if (session('success'))
    <div class="alert alert--success">
        {{ session('success') }}
    </div>
    @endif
  <div class="form-container">
    <h1 class="form-container__title">📄{{ $topic->title }}</h1>

    <div class="topic-detail">
      <div class="topic-detail__header">
        <p class="topic-detail__user">👤 投稿者：{{ $topic->user->name }}</p>
      </div>

      <div class="topic-detail__body">{!! nl2br(e($topic->body)) !!}</div>

      <div class="topic-detail__actions">
        @can('update', $topic)
            <a href="{{ route('topics.edit', $topic->id) }}" class="topic-detail__edit">編集</a>
        @endcan

        @can('delete', $topic)
            <form action="{{ route('topics.destroy', $topic->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="topic-detail__delete" onclick="return confirm('本当に削除しますか？')">削除</button>
            </form>
        @endcan

        <a href="{{ route('topics.index') }}" class="topic-detail__back">一覧に戻る</a>
    </div>
    </div>
  </div>
</x-app-layout>
