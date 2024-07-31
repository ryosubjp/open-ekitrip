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
    <title>駅情報・駅周辺地図を印刷、PDFにする ekitrip(駅トリップ)ユーザーガイド</title>
    <meta name="description" content="駅トリップ（ekitrip）では、駅の地図や情報（乗り換え路線、住所、運営会社）を簡単に印刷またはPDFとして保存することができます。印刷スタイルは3種類から選べるため、駅情報や地図を自分のニーズに合わせて出力できます。旅行やビジネスに役立つこの便利な機能をぜひご活用ください。">


    <link rel="stylesheet" type="text/css" href="../../assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/doc.css">

    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="../../assets/img/favicon/site.webmanifest">

    <?php
    require_once '../../manifest/sv_manifest.php';
    ?>

    <!--googleFont-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">


</head>

<body>
    <div class="header">
        <?php include '../../assets/php/header.php'; ?>
    </div>
    <div class="doc-outframe">
        <div class="breadcrumb-list">
            <img src="../../assets/img/userguide/book.svg">
            <p>ユーザーガイド</p>
        </div>
        <div class="doc-frame">
            <div class="doc-hero">
                <h1 class="doc-title">駅の情報・地図を印刷、PDFにする</h1>
                <h2 class="doc-subtitle">駅トリップ（ekitrip）では、駅の地図や情報（乗り換え路線、住所、運営会社）を簡単に印刷またはPDFとして保存することができます。印刷スタイルは3種類から選べるため、駅情報や地図を自分のニーズに合わせて出力できます。旅行やビジネスに役立つこの便利な機能をぜひご活用ください。</h2>
            </div>
            <div class="doc-content">
                <div class="utilize">
                    <h2 class="content-title">・駅印刷・PDF活用方法のご紹介</h2>
                    <div class="utilize-list">
                        <div class="utilize-list-card">
                            <img src="../../assets/img/userguide/station-print/travel_01_color.png">
                            <h3>旅行やお出かけ</h3>
                            <h5>家族や友人と共有するために駅情報を印刷するのも便利です。紙の地図や情報を持っていると、スマートフォンのバッテリーを気にせずに済みます。</h5>
                        </div>
                        <div class="utilize-list-card">
                            <img src="../../assets/img/userguide/station-print/smartphone_middleaged_suit_man_color.png">
                            <h3>オフラインでの閲覧</h3>
                            <h5>PDFとしてスマートフォンやタブレットに保存しておけば、移動中など、ネット環境が不安定な場所でも安心です。</h5>
                        </div>
                    </div>
                </div>
                <h2 class="content-title">・駅情報印刷機能の使い方</h2>
                <div class="step-card">
                    <p class="step-call">STEP1</p>
                    <h3>印刷したい駅を検索</h3>
                    <h4>右上の「駅を検索」からも検索画面に移動できます</h4>
                    <img class="step-card-img" src="../../assets/img/userguide/station-print/station-print-step1.gif">
                </div>
                <div class="step-next"><img src="../../assets/img/userguide/step-next.svg"></div>
                <div class="step-card">
                    <p class="step-call">STEP2</p>
                    <h3>駅ページを開く</h3>
                    <h4>検索結果から印刷する路線と駅の「開く」を押します</h4>
                    <img class="step-card-img" src="../../assets/img/userguide/station-print/station-print-step2.png">
                </div>
                <div class="step-next"><img src="../../assets/img/userguide/step-next.svg"></div>
                <div class="step-card">
                    <p class="step-call">STEP3</p>
                    <h3>メニューのその他を開く</h3>
                    <h4>駅情報ページの駅名下にあるメニューの「その他」を押す</h4>
                    <img class="step-card-img" src="../../assets/img/userguide/station-print/station-print-step3.gif">
                </div>
                <div class="step-next"><img src="../../assets/img/userguide/step-next.svg"></div>
                <div class="step-card">
                    <p class="step-call">STEP4</p>
                    <h3>「駅情報を印刷」ボタンを押す</h3>
                    <h4>一番上にある「駅情報を印刷」ボタンを押します</h4>
                </div>
                <div class="step-next"><img src="../../assets/img/userguide/step-next.svg"></div>
                <div class="step-card">
                    <p class="step-call">STEP5</p>
                    <h3>印刷スタイルの選択</h3>
                    <h4>3種類の印刷スタイルから用途にあったスタイルを選択してください</h4>
                </div>
                <div class="step-next"><img src="../../assets/img/userguide/step-next.svg"></div>
                <div class="step-card">
                    <p class="step-call">(印刷)STEP5</p>
                    <h3>駅情報・地図を印刷する</h3>
                    <h4>印刷専用画面に移動し数秒すると印刷確認画面が表示されます（OS・ブラウザにより表示、操作が異なります）</h4>
                    <p class="caution-text">ご注意：Safariを使用している場合「自動的に印刷することは禁止されています」と表示される場合がありますが「許可」をタップしてください。
                    <p>
                </div>
                <div class="step-card" style="margin-top: 20px;" id="pdf-print">
                    <p class="step-call">(PDF)STEP5</p>
                    <h3>駅情報・地図PDFを作成する場合</h3>
                    <h4>PDFに出力する場合OSやブラウザにより操作が大きく異なるため以下の情報をご確認ください。</h4>
                    <p style="padding-top: 10px; font-size:12px;">Android(Chrome)：「プリンタを選択」の部分をタップし、「PDF形式で保存」を選択します。画面右上の「PDF」ボタンをタップすると、PDFファイルとして保存されます。保存先のフォルダを選択し、ファイル名を付けて保存しましょう。</p>
                    <p style="padding-top: 10px; font-size:12px;">Windows(Chrome)：「宛先」または「送信先」の項目で、「PDFに保存」を選択します。「保存」ボタンをクリックすると、PDFファイルとして保存されます。保存先のフォルダを選択し、ファイル名を付けて保存しましょう。</p>

                </div>
            </div>


        </div>
    </div>

    <?php include '../../assets/php/nav-bar.php'; ?>

</body>
<script src="../../assets/js/escape.js"></script>
<script src="../../assets/js/settings.js"></script> <!-- 設定の読み込み -->
<script src="../../assets/js/modal.js"></script>
<script>
    window.onload = function() {
        settings();
    }

    function beta_err() {
        window.alert('この機能はBETA版では利用できません');
    }
</script>

</html>