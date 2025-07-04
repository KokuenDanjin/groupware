document.addEventListener('DOMContentLoaded', () => {
    // ユーザー選択じのページ遷移
    const userSelect = document.getElementById('participantUserSelect');
    userSelect.addEventListener('change', function() {
        const selectedUserId = this.value;
        const currentUrl = new URL(window.location.href);

        currentUrl.searchParams.set('userId', selectedUserId);

        window.location.href = currentUrl.toString();
    })

    // スケジュールパネルの遷移イベント（詳細画面へ）
    const schedulePanels = Array.from(document.getElementsByClassName('schedule-panel'));
    schedulePanels.forEach(panel => {
        panel.addEventListener('click', function() {
            const scheduleId = this.dataset.scheduleId;
            if (scheduleId) {
                window.location.href = `/schedule/${scheduleId}`;    
            }
        });
    });
})

