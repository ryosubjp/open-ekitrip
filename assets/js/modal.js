document.addEventListener('DOMContentLoaded', (event) => {
    // モーダルを開くボタンをすべて取得
    var openModalBtns = document.querySelectorAll('.openModalBtn');

    // それぞれのボタンにクリックイベントを追加
    openModalBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var modalId = this.getAttribute('data-modal');
            var modal = document.getElementById(modalId);
            modal.style.display = "block";
        });
    });

    // 全ての閉じるボタンを取得し、クリックイベントを追加
    var closeBtns = document.querySelectorAll('.close-btn');
    closeBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var modal = this.closest('.modal');
            modal.style.display = "none";
        });
    });

});

