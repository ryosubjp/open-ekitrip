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
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'G-X3VSZBRGMG');
        }
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ekitrip(駅トリップ) 履歴- 旅行者のための駅情報と訪問駅記録アプリ</title>

    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">

    <link rel="apple-touch-icon" sizes="180x180" href="../assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/img/favicon/site.webmanifest">

    <!--googleFont-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">


</head>

<body>
    <div class="header">
        <?php include '../assets/php/header.php'; ?>
    </div>
    <div class="history-content">
        <div class="history-page-nav">
            <a href="../" class="history-page-nav-btn">戻る</a>
            <a href="../menu#menu-history" class="history-setting-link">履歴の設定</a>
        </div>
        <div class="history-list" id="history_list"></div>
    </div>

    <?php include '../assets/php/nav-bar.php'; ?>
    <style>
        .history-content {
            padding: 80px 20px;
        }

        .history-page-nav {
            padding: 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .history-page-nav-btn {
            text-decoration: none;
            color: #333333;
            background-color: #F8F8F8;
            padding: 10px 25px;
            border-radius: 60px;
        }

        .history-setting-link {
            text-decoration: none;
            color: #2E86DE;
            font-size: 12px;
        }

        .history-type-icon {
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #F8F8F8;
            border-radius: 100px;
            margin-right: 10px;
        }

        .history-list-btn {
            display: flex;
            justify-content: left;
            align-items: center;
            text-decoration: none;
            margin-bottom: 10px;
            color: #333333;
        }

        .history-info h3 {
            font-size: 15px;
        }

        .history-info p {
            font-size: 12px;
        }
    </style>
</body>
<script src="../assets/js/escape.js"></script>
<script src="../assets/js/settings.js"></script> <!-- 設定の読み込み -->
<script>
    window.onload = function() {
        settings();

        if (settingsData.function_switch.fn_se5 === 'on') {
            const history_list = document.getElementById('history_list');
            const get_history = localStorage.getItem('history');
            const historyData = JSON.parse(get_history);
            let count = 0;

            if (historyData) {
                for (let i = 0; i <= historyData.length - 1; i++) {
                    count++;
                    history_list.innerHTML += `<a href="../station/?stacd=${escapeHtml(historyData[i].code)}" class="history-list-btn"><div class="history-type-icon"><img src="../assets/img/station/train.svg"></div><div class="history-info"><h3>${escapeHtml(historyData[i].name)}駅</h3><p>${escapeHtml(historyData[i].linename)}</p></div></a>`;
                }
                if (count < 1) {
                    history_list.innerHTML += `<p>閲覧した駅はありません</p>`;
                }
            } else {
                history_list.innerHTML += `<p>閲覧した駅はありません</p>`;
            }
        } else {
            history_list.innerHTML += `<p>履歴機能はOFFになっています。</p>`;
        }

    }
</script>


</html>