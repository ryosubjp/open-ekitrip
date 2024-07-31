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
    <title>ekitrip(駅トリップ) ユーザーガイド- 旅行者のための駅情報と訪問駅記録アプリ</title>

    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/doc.css">

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
    <div class="doc-frame">
        <div class="userguide-hero">
            <h1><span>ekitrip.com</span><br>ユーザーガイド</h1>
            <h2>駅トリップ（ekitrip）の使い方や活用方法を掲載しています。</h2>
        </div>

    </div>
    <?php include '../assets/php/nav-bar.php'; ?>

</body>
<script src="../assets/js/escape.js"></script>
<script src="../assets/js/settings.js"></script> <!-- 設定の読み込み -->
<script src="../assets/js/modal.js"></script>
<script>
    window.onload = function() {
        settings();
    }

    function beta_err() {
        window.alert('この機能はBETA版では利用できません');
    }

</script>

</html>