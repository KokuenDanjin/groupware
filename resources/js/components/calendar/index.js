
const schedulePanels = Array.from(document.getElementsByClassName('schedule-panel'));

schedulePanels.forEach(panel => {
    panel.addEventListener('click', function() {
        const scheduleId = this.dataset.scheduleId;
        if (scheduleId) {
            window.location.href = `/schedule/${scheduleId}`;    
        }
    });
});