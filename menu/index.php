<!DOCTYPE html>
<html lang="ja">

<head>

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-X3VSZBRGMG"></script>
    <script>
        // localStorageからadmin_userの値を取得
        const storedAdminUser = localStorage.getItem('admin_user');
        let isAdminUser = false; // 初期値をfalseに設定

        // localStorageに値が保存されていた場合
        if (storedAdminUser) {
            try {
                // JSON形式でパース
                isAdminUser = JSON.parse(storedAdminUser);
                console.log('プレビューモードでアクセスします');
            } catch (error) {
                console.error('localStorageから値を取得できませんでした:', error);
            }
        }

        // 管理ユーザーでない場合にのみGoogleアナリティクスを実行
        if (!isAdminUser) {
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', 'G-X3VSZBRGMG');
        }
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ekitrip(駅トリップ) メニュー- 旅行者のための駅情報と訪問駅記録アプリ</title>

    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/menu.css">

    <link rel="apple-touch-icon" sizes="180x180" href="../assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/img/favicon/site.webmanifest">

    <?php
    require_once '../manifest/sv_manifest.php';
    ?>

    <!--googleFont-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">


</head>

<body>
    <div class="header">
        <?php include '../assets/php/header.php'; ?>
    </div>
    <div class="menu-content">
        <div class="user-card">
            <div class="user-card-info">
                <img class="user-icon" src="../assets/img/menu/user.svg">
                <div>
                    <h5 id="user_name"></h5>
                </div>
            </div>
            <!--<div class="user-achievement">
                <div>
                    <h5>実績バッチ</h5>
                </div>
                <div class="achievement-card-list">
                    <div class="achievement-card">
                        <img src="../assets/img/menu/sv_user_birthday.svg">
                        <p id="achievement_date"></p>
                        <p>利用開始</p>
                    </div>
                    <div class="achievement-card">
                        <img src="../assets/img/menu/batch-none.svg">
                        <p id="achievement_date"></p>
                        <p>20駅達成</p>
                    </div>
                </div>
            </div>-->
        </div>
        <h2>設定</h2>
        <div class="menu-frame">
            <button data-modal="user_name_modal" class="openModalBtn menu-btn border-none">
                <div>
                    <img class="menu-icon" src="">
                    <p>ユーザー名変更</p>
                </div>
                <img src="../assets/img/menu/right.svg">
            </button><!--menu-btn-->
        </div>
        <div class="menu-frame">
            <button onclick="visit_all_del()" class="menu-btn border-none">
                <div>
                    <img class="menu-icon" src="">
                    <p>訪問履歴削除</p>
                </div>
                <img src="../assets/img/menu/right.svg">
            </button><!--menu-btn-->
        </div>
        <div id="menu-history" class="menu-frame">
            <button onclick="history_setting()" class="menu-btn border-none">
                <div>
                    <img class="menu-icon" src="">
                    <p>履歴機能</p>
                </div>
                <p id="history_setting_status"></p>
            </button><!--menu-btn-->
            <button onclick="history_all_del()" class="menu-btn">
                <div>
                    <img class="menu-icon" src="">
                    <p>履歴の削除</p>
                </div>
                <img src="../assets/img/menu/right.svg">
                </a><!--menu-btn-->
        </div>
        <div id="menu_data_migration" class="menu-frame">
            <button data-modal="data_migration" class="openModalBtn menu-btn border-none">
                <div>
                    <img class="menu-icon" src="">
                    <p>データ移行</p>
                </div>
                <img src="../assets/img/menu/right.svg">
            </button><!--menu-btn-->
            <button data-modal="data_import" class="openModalBtn menu-btn">
                <div>
                    <img class="menu-icon" src="">
                    <p>データインポート</p>
                </div>
                <img src="../assets/img/menu/right.svg">
            </button><!--menu-btn-->
        </div>
        <div class="menu-frame">
            <button onclick="Initialization()" class="menu-btn border-none">
                <div>
                    <img class="menu-icon" src="">
                    <p>初期化</p>
                </div>
                <img src="../assets/img/menu/right.svg">
            </button><!--menu-btn-->
        </div>
        <h2>サービス情報</h2>
        <div class="menu-frame">
            <div class="menu-btn border-none">
                <div>
                    <img class="menu-icon" src="">
                    <p>バージョン</p>
                </div>
                <p><?php echo $sv_version_name; ?></p>
            </div><!--menu-btn-->
            <div class="menu-btn">
                <div>
                    <img class="menu-icon" src="">
                    <p>データバージョン</p>
                </div>
                <p><?php echo $db_version_name; ?></p>
            </div><!--menu-btn-->
        </div>

    </div>
    <?php include '../assets/php/nav-bar.php'; ?>

    <!--modal-->
    <?php include '../assets/php/fast_access.php'; ?>

    <div id="user_name_modal" class="modal">
        <div class="modal-back">
            <div class="modal-content">
                <div class="modal-title">
                    <div>
                        <h2>ユーザ名変更</h2>
                    </div>
                    <img class="close-btn" src="../assets/img/station/close.svg">
                </div>
                <div class="new-user-name-setting">
                    <input class="new-user-name" type="text" id="new_user_name" placeholder="新しいユーザー名を入力"><br>
                    <button class="new-user-btn" onclick="new_user_name()">変更</button>
                </div>

            </div><!--modal-content-->
        </div>
    </div>

    <div id="data_migration" class="modal">
        <div class="modal-back">
            <div class="modal-content">
                <div class="modal-title">
                    <div>
                    </div>
                    <img class="close-btn" src="../assets/img/station/close.svg">
                </div>
                <div class="data_migration-title">
                    <img src="../assets/img/menu/data_migration.svg">
                    <h2>データを移行</h2>
                    <p>ユーザーの設定情報、訪問履歴、お気に入り駅、その他サービスに関するユーザーがカスタマイズしたデータをほかのデバイスやブラウザに移行できます。</p>
                </div>
                <p>移行の方法を選択</p>
                <button data-modal="data_migration_codecopy" class="openModalBtn migration-select-card">
                    <img src="../assets/img/menu/code.svg">
                    <div>
                        <h4>コードをコピー</h4>
                        <p>設定情報が記載されたコードをコピーして移行後のブラウザや新しい端末に貼り付けることでデータを移行できます</p>
                    </div>
                </button>
                <!--<button data-modal="data_migration_codecopy" class="openModalBtn migration-select-card">
                    <img src="../assets/img/menu/cloud.svg">
                    <div>
                        <h4>クラウドにアップロード</h4>
                        <p>設定情報をクラウドにアップロードします。バックアップIDを移行先のブラウザや端末に貼り付けることで移行できます</p>
                    </div>
                </button>-->

            </div><!--modal-content-->
        </div>
    </div>

    <div id="data_migration_codecopy" class="modal">
        <div class="modal-back">
            <div class="modal-content">
                <div class="modal-title">
                    <div>
                    </div>
                    <img class="close-btn" src="../assets/img/station/close.svg">
                </div>
                <div class="data_migration-title">
                    <img src="../assets/img/menu/code.svg">
                    <h2>移行用コードの生成</h2>
                    <p>設定情報が記載されたコードを生成します。コードをコピーして移行後のブラウザや新しい端末に貼り付けることでデータを移行できます</p>
                    <button class="codeGenerate-btn" onclick="data_migration_codeGenerate()">移行用コードをコピー</button>
                </div>
                <div class="migration-code" id="migration-code">
                    <p>＊コピーしました</p>
                    <textarea class="migration_codeGenerate" id="migration_codeGenerate" readonly></textarea>
                </div>

            </div><!--modal-content-->
        </div>
    </div>

    <div id="data_import" class="modal">
        <div class="modal-back">
            <div class="modal-content">
                <div class="modal-title">
                    <div>
                    </div>
                    <img class="close-btn" src="../assets/img/station/close.svg">
                </div>
                <div class="data_migration-title">
                    <img src="../assets/img/menu/import.svg">
                    <h2>データをインポート</h2>
                    <p>設定情報が記載されたコードをペーストしてデータをインポートします。<br><span class="import-alert">データをインポートすると現在保存されている設定情報や訪問履歴、お気に入りの情報は削除されインポートした情報に上書きされます。</span></p>
                </div>
                <div class="import">
                    <textarea class="import-code" type="text" id="migration_code" placeholder="ここにコードを貼り付け"></textarea>
                    <button onclick="migration_code_import()" class="codeGenerate-btn">インポート</button>
                </div>

            </div><!--modal-content-->
        </div>
    </div>
</body>
<script src="../assets/js/escape.js"></script>
<script src="../assets/js/settings.js"></script> <!-- 設定の読み込み -->
<script src="../assets/js/modal.js"></script>
<script>
    const history_setting_status = document.getElementById('history_setting_status');
    window.onload = function() {
        settings();
        if (settingsData.fase_access_detector === 'yes') {
            const fast_access = document.getElementById('fast_access');
            fast_access.style.display = 'block';
            settingsData.fase_access_detector = 'no';
            localStorage.setItem('sv_settings', JSON.stringify(settingsData));
        }
        const user_name = document.getElementById('user_name');
        user_name.innerText += `ようこそ、${escapeHtml(settingsData.user_name)}さん`;




        function formatDate(dateStr) {
            const year = dateStr.substring(0, 4);
            const month = dateStr.substring(4, 6);
            const day = dateStr.substring(6, 8);
            return `${year}/${parseInt(month, 10)}/${parseInt(day, 10)}`;
        }


        if (settingsData.function_switch.fn_se5 === 'off') {
            history_setting_status.innerText += `無効`;
        } else {
            history_setting_status.innerText += `有効`;
        }

    }

    function new_user_name() {
        const new_name = escapeHtml(document.getElementById('new_user_name').value);
        if (new_name === null || new_name === undefined || new_name.trim() === '') {
            window.alert('エラーが発生しました');
        } else {
            settingsData.user_name = new_name;
            localStorage.setItem('sv_settings', JSON.stringify(settingsData));
            window.alert('変更しました');
            location.reload();
        }
    }

    function beta_err() {
        window.alert('この機能はBETA版では利用できません');
    }



    //visit all del
    function visit_all_del() {
        if (confirm('訪問履歴をすべて削除しますか。削除後データの復元はできません')) {
            localStorage.removeItem('visit_history');
            window.alert('削除しました。');
            location.reload();
        }
    }

    //history

    function history_setting() {
        if (settingsData.function_switch.fn_se5 === 'off') {
            if (confirm('履歴機能を有効にしますか')) {
                settingsData.function_switch.fn_se5 = 'on';
                localStorage.setItem('sv_settings', JSON.stringify(settingsData));
                location.reload();
            }
        } else {
            if (confirm('履歴機能を無効にしますか')) {
                settingsData.function_switch.fn_se5 = 'off';
                localStorage.setItem('sv_settings', JSON.stringify(settingsData));
                location.reload();
            }
        }
    }

    function history_all_del() {
        if (confirm('履歴をすべて削除しますか。削除後データの復元はできません')) {
            localStorage.removeItem('history');
            window.alert('削除しました。');
            location.reload();
        }
    }

    function Initialization() {
        if (confirm('この操作により、以下のすべてのデータが削除されます　・履歴 ・訪問履歴 ・お気に入り駅 ・設定情報　/この操作は元に戻すことができません。 本当に実行してよろしいですか？ ')) {
            if (confirm('再度の確認です。本当に以下のすべてのデータを削除してよろしいですか？　・履歴 ・訪問履歴 ・お気に入り駅 ・設定情報')) {
                localStorage.removeItem('history');
                localStorage.removeItem('favorite');
                localStorage.removeItem('visit_history');
                localStorage.removeItem('sv_settings');
                window.alert('初期化しました');
                location.reload();
            }
        }
    }

    function data_migration_codeGenerate() {
        const settings_array = JSON.parse(localStorage.getItem('sv_settings'));
        const visit_array = JSON.parse(localStorage.getItem('visit_history'));
        const favorite_array = JSON.parse(localStorage.getItem('favorite'));
        const migration_data = {
            dataCodeVer: 1,
            settings: settings_array,
            visit: visit_array,
            favorite: favorite_array,
        }

        const migration_dataCode = JSON.stringify(migration_data);
        const migration_codeGenerate = document.getElementById('migration_codeGenerate');
        migration_codeGenerate.innerText = migration_dataCode;
        const migration_code_disp = document.getElementById('migration-code');
        migration_code_disp.style.display = 'block'
        navigator.clipboard.writeText(migration_dataCode);
    }

    function migration_code_import() {
        if (confirm('データをインポートすると現在保存されている設定情報や訪問履歴、お気に入りの情報は削除されインポートした情報に上書きされます。実行してよしいですか')) {
            const import_code = document.getElementById('migration_code').value;
            const migration_code = JSON.parse(import_code);

            localStorage.removeItem('sv_settings');
            localStorage.setItem('sv_settings', JSON.stringify(migration_code.settings));

            if (migration_code.favorite != null) {
                localStorage.removeItem('favorite');
                localStorage.setItem('favorite', JSON.stringify(migration_code.favorite));
            }

            if (migration_code.visit != null) {
                localStorage.removeItem('visit_history');
                localStorage.setItem('visit_history', JSON.stringify(migration_code.visit));
            }
            window.alert('移行が完了しました');
            location.reload();


        }
    }
</script>

</html>