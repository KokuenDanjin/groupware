@vite(['resources/js/pages/schedule.js', 'resources/js/components/schedule/index.js'])
<x-app-layout>
    <div class="schedule-main">
        <div class="schedule-container">
            @include('schedule.components.back-to-calendar-button')
            <form method="POST" action="{{ route('schedule.store') }}">

                <ul class="schedule__nav">
                    <li>
                        <button class="schedule__nav__button schedule__nav__button-save">
                            <span class="material-symbols-outlined schedule__nav__icon">save</span> 
                            <span>保存</span>
                        </button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</x-app-layout>