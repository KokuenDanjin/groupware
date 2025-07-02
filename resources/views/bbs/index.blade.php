@extends('layouts.app') 

@section('content')
  <h1>掲示板</h1>

  <a href="create.html" class="new-post-button">＋ 新規投稿</a>

  <div class="topic-list">
    <div class="topic-item">
      <div class="topic-title">例のタイトル1</div>
      <div class="topic-body">これは投稿本文の一部です。内容がここに入ります。</div>
      <div class="topic-meta">投稿者: ユーザーA ／ 投稿日: 2025-07-02</div>
      <a href="detail.html?id=1" class="read-more">続きを読む</a>
    </div>

    <div class="topic-item">
      <div class="topic-title">お知らせ：夏季休暇について</div>
      <div class="topic-body">8月10日から15日までの間、夏季休業となります。</div>
      <div class="topic-meta">投稿者: 管理者 ／ 投稿日: 2025-06-28</div>
      <a href="detail.html?id=2" class="read-more">続きを読む</a>
    </div>

    <div class="topic-item">
      <div class="topic-title">雑談トピック</div>
      <div class="topic-body">最近見た映画とかありますか？</div>
      <div class="topic-meta">投稿者: ユーザーB ／ 投稿日: 2025-06-25</div>
      <a href="detail.html?id=3" class="read-more">続きを読む</a>
    </div>
  </div>
@endsection
