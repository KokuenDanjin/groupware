document.addEventListener('DOMContentLoaded', () => {
    console.log('適用開始');
    const timelineStartHour = 8; // PHP側 getAvailabilityTimeと同じ
    const timelineEndHour = 21;  // PHP側 getAvailabilityTimeと同じ (endTime + 1)
    
    const panels = Array.from(document.querySelectorAll('.schedule-panel'));
    const scheduleCells = Array.from(document.querySelectorAll('.calendar__hour'));
    const undeterminedContainer = document.querySelector('.undetermined-events');

    const cellHeight = scheduleCells[0]?.offsetHeight || 60;
    const topOffset = undeterminedContainer?.offsetHeight || 0;

    const timelineEvents = [];
    const undeterminedEvents = [];

    // 1. イベント分類
    panels.forEach(panel => {
        const timeType = panel.dataset.timeType;
        const start = new Date(panel.dataset.start);
        const end = new Date(panel.dataset.end);

        const isUndetermined =
            timeType === 'undecided' ||
            end.getHours() < timelineStartHour ||
            start.getHours() >= timelineEndHour;

        if (!isUndetermined) {
            timelineEvents.push({ panel, start, end, timeType });
        }
    });

    // 2. 重複グループ作成
    function groupOverlappingEvents(events) {
        const groups = [];
        const visited = new Set();

        const overlap = (a, b) => a.start < b.end && b.start < a.end;

        events.forEach((ev, i) => {
            if (visited.has(i)) return;

            const stack = [i];
            const group = [];

            while(stack.length) {
                const idx = stack.pop();
                if (visited.has(idx)) continue;
                visited.add(idx);
                group.push(events[idx]);

                events.forEach((other, j) => {
                    if (!visited.has(j) && overlap(events[idx], other)) {
                        stack.push(j);
                    }
                });
            }
            groups.push(group);
        });
        return groups;
    }

    const groups = groupOverlappingEvents(timelineEvents);

    // 3. 列割り当てとstyle適用
    groups.forEach(group => {
        // 優先度: all_day > multi_day_normal > normal
        const priority = { 'all_day':1, 'multi_day_normal':2, 'normal':3 };
        group.sort((a,b) => {
            if (priority[a.timeType] !== priority[b.timeType]) return priority[a.timeType] - priority[b.timeType];
            return a.start - b.start;
        });

        // カラム割り当てだけ行う
        const columns = [];
        group.forEach(ev => {
            // top / height 計算
            const startHour = Math.max(ev.start.getHours() + ev.start.getMinutes()/60, timelineStartHour);
            const endHour = Math.min(ev.end.getHours() + ev.end.getMinutes()/60, timelineEndHour);
            const top = topOffset + (startHour - timelineStartHour) * cellHeight;
            const height = (endHour - startHour) * cellHeight;

            // 横並び計算
            let colIndex = null;
            for(let i=0;i<columns.length;i++) {
                if (ev.start >= columns[i]) {
                    colIndex = i;
                    columns[i] = ev.end;
                    break;
                }
            }
            if (colIndex === null) {
                colIndex = columns.length;
                columns.push(ev.end);
            }

            // 一旦 colIndex を記録しておく
            ev._colIndex = colIndex;
            ev._top = top;
            ev._height = height;
        });

        // 全体のカラム数が確定 → 幅を計算してstyle適用
        const totalCols = columns.length;
        group.forEach(ev => {
            const left = (ev._colIndex / totalCols) * 100;
            const width = 100 / totalCols;

            ev.panel.style.position = 'absolute';
            ev.panel.style.top = `${ev._top}px`;
            ev.panel.style.height = `${ev._height}px`;
            ev.panel.style.left = `${left}%`;
            ev.panel.style.width = `${width}%`;
            ev.panel.style.boxSizing = 'border-box';

            // 不要になった作業用プロパティを削除
            delete ev.colIndex;
            delete ev._top;
            delete ev._height;
        });
    });
    console.log('適用完了');
});
