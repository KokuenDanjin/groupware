document.addEventListener('DOMContentLoaded', () => {
    // 日時入力欄の表示/非表示制御
    const timeInputs = document.querySelectorAll('.schedule-form__time-input');

    function toggleTimeInputs(value) {
        if (value === 'normal') {
            timeInputs.forEach(input => {
                input.parentElement.style.display = 'inline-block';
            });
        } else {
            timeInputs.forEach(input => {
                input.parentElement.style.display = 'none';
                input.value = ''; // 念のため入力値もクリア
            });
        }
    } 

    // 初期状態
    const checkedRadio = document.querySelector('input[name="time_type"]:checked');
    if (checkedRadio) {
        toggleTimeInputs(checkedRadio.value);
    }

    // ラジオ切り替え時の制御
    document.querySelectorAll('input[name="time_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            toggleTimeInputs(this.value);
        });
    });


    // multiple属性付きselectタグの制御
    // シングルクリックで選択をトグル可能にする
    const multiples = document.querySelectorAll('select[multiple]');
    multiples.forEach(multiple => {
        Array.from(multiple.options).forEach(option => {
            option.addEventListener('mousedown', function(e) {
                e.preventDefault();
                this.selected = !this.selected;
            });
        });
    })
});