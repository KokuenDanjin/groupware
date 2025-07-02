@vite(['resources/js/pages/schedule.js', 'resources/js/components/schedule/index.js'])
<x-app-layout>
    <div class="schedule-main">
        <div class="schedule-container">
            @include('schedule.components.back-to-calendar-button')
            <form class="schedule-form" method="POST" action="{{ $mode === 'edit' ? route('schedule.update', ['id' => $id ]) : route('schedule.store') }}">
                @csrf
                <div class="schedule-form__title-block">
                    <div class="schedule-form__title">
                        <div class="schedule-form__title-label schedule-form__main-label">タイトル</div>
                        <div class="schedule-form__title-input">
                            <select id="schedule-category" class="schedule-form__category-select" name="category_id">
                                @forelse($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $schedule->category_id ?? '') === $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @empty
                                <option disabled>カテゴリがありません</option>
                                @endforelse
                            </select>
                            <input class="schedule-form__title-input-text" type="text" name="title" placeholder="タイトルを入力" value="{{ old('title', $schedule->title ?? '') }}" maxlength="255" required>
                        </div>
                    </div>
                </div>

                <div class="schedule-form__datetime-block">
                    <div class="schedule-form__datetime">
                        <div class="schedule-form__datetime-label schedule-form__main-label">日時</div>
                        <div>
                            <div class="schedule-form__datetime-time-type">
                                @php
                                    $time_type = old('time_type') ?? $schedule->time_type ?? 'normal'; 
                                @endphp
                                <label class="schedule-form__time-type-label schedule-form__label-for-radio" for="time-type-normal">
                                    <input id="time-type-normal" class="schedule-form__time-type-input" type="radio" name="time_type" value="normal" {{ $time_type === 'normal' ? 'checked' : '' }}>
                                    通常
                                </label>
                                <label class="schedule-form__time-type-label schedule-form__label-for-radio" for="time-type-all_day">
                                    <input id="time-type-all_day" class="schedule-form__time-type-input" type="radio"name="time_type" value="all_day" {{ $time_type === 'all_day' ? 'checked' : '' }}>
                                    終日
                                </label>
                                <label class="schedule-form__time-type-label schedule-form__label-for-radio" for="time-type-undecided">
                                    <input id="time-type-undecided" class="schedule-form__time-type-input" type="radio" name="time_type" value="undecided" {{ $time_type === 'undecided' ? 'checked' : '' }}>
                                    時間未定
                                </label>
                            </div>
                            <div class="schedule-form__datetime-datetime">
                                <div class="schedule-form__datetime-datetime-start">
                                    <input class="schedule-form__start-date-input schedule-form__date-input" type="date" name="start_date" value="{{ old('start_date', $date ?? $schedule->start_date ?? '') }}" required>
                                    <div class="schedule-form__start-time-wrapper">
                                        <input class="schedule-form__start-time-input schedule-form__time-input" type="time" name="start_time" value="{{ old('start_time', $schedule->start_time ?? '') }}">
                                    </div>
                                </div>
                                <div>～</div>
                                <div class="schedule-form__datetime-datetime-end">
                                    <input class="schedule-form__end-date-input schedule-form__date-input" type="date" name="end_date" value="{{ old('end_date', $date ?? $schedule->end_date ?? '') }}" required>
                                    <div class="schedule-form__end-time-wrapper">
                                        <input class="schedule-form__end-time-input schedule-form__time-input" type="time" name="end_time" value="{{ old('end_time', $schedule->end_time ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="schedule-form__participants-block">
                    <div class="schedule-form__participants">
                        <div class="schedule-form__participants-label schedule-form__main-label">参加者</div>
                        <select id="participant" class="schedule-form__participants-select" name="participants[]" multiple>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id, old('participants', $participants ?? [])) ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="schedule-form__memo-block">
                    <div class="schedule-form__memo">
                        <div class="schedule-form__memo-label schedule-form__main-label">メモ</div>
                        <textarea id="schedule-memo" class="schedule-form__memo-textarea" name="memo" placeholder="メモを追加" maxlength="65535">{{ old('memo', $schedule->memo ?? '') }}</textarea>
                    </div>
                </div>

                <div class="schedule-form__private-flg-block">
                    <div class="schedule-form__private-flg-label schedule-form__main-label">公開方法</div>
                    <div class="schedule-form__private-flg">
                        @php
                            $private_flg = old('private_flg') ?? $schedule->private_flg ?? 0; 
                        @endphp
                        <label class="schedule-form__private-label schedule-form__label-for-radio" for="private-flg--private">
                            <input id="private-flg--private" class="schedule-form__private-flg-input" type="radio" name="private_flg" value="0" {{ $private_flg === 0 ? 'checked' : '' }}>
                            公開
                        </label>
                        <label class="schedule-form__public-label schedule-form__label-for-radio" for="private-flg--public">
                            <input id="private-flg--public" class="schedule-form__private-flg-input" type="radio" name="private_flg" value="1" {{ $private_flg === 1 ? 'checked' : '' }}>
                            非公開
                        </label>
                    </div>
                </div>

                <ul class="schedule__nav">
                    <li>
                        <button type="submit" class="schedule__nav__button schedule__nav__button-save">
                            <span class="material-symbols-outlined schedule__nav__icon">save</span> 
                            <span>保存</span>
                        </button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</x-app-layout>