@vite(['resources/js/pages/topics.js'])
<x-app-layout>
  <div class="form-container">
    <h1 class="form-container__title">📝 新規投稿</h1>

    <form action="{{ route('topics.store') }}" method="POST" class="topic-form">
      @csrf

      <div class="topic-form__field">
        <label for="title" class="topic-form__label">タイトル</label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" class="topic-form__input">
        @error('title')
          <p class="topic-form__error">{{ $message }}</p>
        @enderror
      </div>

      <div class="topic-form__field">
        <label for="body" class="topic-form__label">本文</label>
        <textarea id="body" name="body" rows="6" class="topic-form__textarea">{{ old('body') }}</textarea>
        @error('body')
          <p class="topic-form__error">{{ $message }}</p>
        @enderror
      </div>

      <div class="topic-form__actions">
        <button type="submit" class="topic-form__submit">投稿する</button>
        <a href="{{ route('topics.index') }}" class="topic-form__cancel">キャンセル</a>
      </div>
    </form>
  </div>
</x-app-layout>
