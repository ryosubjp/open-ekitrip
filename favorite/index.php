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
    <title>ekitrip(駅トリップ) お気に入りの駅- 旅行者のための駅情報と訪問駅記録アプリ</title>
    <meta name="description" content="ekitrip(駅トリップ)（ekitrip.com）の駅保存機能で、よく使う駅やお気に入りの駅情報にすぐアクセス！駅を保存し、任意のメモを追加して特記事項や予定を記録できます。「この駅で待ち合わせ」などのメモも可能。ekitrip(駅トリップ)で、あなたの旅行や日常の移動をより便利に管理しましょう。">


    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/index.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/favorite.css">

    <link rel="apple-touch-icon" sizes="180x180" href="../assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/img/favicon/site.webmanifest">

    <!--googleFont-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>

</head>

<body>
    <div class="header">
        <?php include '../assets/php/header.php'; ?>
    </div>
    <div class="hero-back">
        <div class="hero-back-effect"></div>
        <div id="small_view_map" class="small-view-map"></div>
    </div>
    <div class="hero">
        <img class="title-logo" src="../assets/img/favorite/favorite-icon.svg">
        <h1>保存した駅</h1>
        <h2>よく使う駅やお気に入りの駅情報を<br>すぐに確認できるようになります</h2>
    </div>
    <div class="content-area">
        <div class="browser-alert" id="browser_alert">
            <img src="../assets/img/error/warning.svg" width="40px">
            <div>
                <p>非推奨ブラウザです</p>
                <p class="browser-alert-text">この機能を使用する場合デフォルトブラウザ（SafariやChrome）などで開いてください。<br><span>すでにデータを保存している場合<a href="https://ekitrip.com/menu/#menu_data_migration">移行機能</a>をご利用いただけます</span></p>
                <button onclick="BA_copyButton('https://ekitrip.com/favorite/')">リンクコピー</button>
            </div>
        </div>
        <!--favorite-list-->
        <div class="favorite-list-frame">
            <div class="favorite-list" id="favorite_list"></div>
        </div>
        <?php include '../assets/php/footer.php'; ?>
    </div>
    <?php include '../assets/php/nav-bar.php'; ?>

    <?php include '../assets/php/fast_access.php'; ?>
</body>
<script src="../assets/js/escape.js"></script>
<script src="../assets/js/browser_judge.js"></script>
<script src="../assets/js/settings.js"></script> <!-- 設定の読み込み -->
<script src="../assets/js/modal.js"></script> <!-- 設定の読み込み -->
<script>
    const lat = 35.6806;
    const lon = 139.7669;

    //緯度,経度,ズーム
    var map = L.map('small_view_map').setView([lat, lon], 14);
    // OpenStreetMap から地図画像を読み込む
    L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        maxZoom: 14,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, '
    }).addTo(map);

    window.onload = function() {

        settings();
        browser_judge();

        if (settingsData.fase_access_detector === 'yes') {
            const fast_access = document.getElementById('fast_access');
            fast_access.style.display = 'block';
            settingsData.fase_access_detector = 'no';
            localStorage.setItem('sv_settings', JSON.stringify(settingsData));
        }

        //favorite-index
        const get_favoriteArray = localStorage.getItem('favorite');
        const favoriteArray = JSON.parse(get_favoriteArray);
        const favorite_list = document.getElementById('favorite_list');
        let count = '';

        if (favoriteArray) {
            //favoriteを使用している
            const favorite_length = favoriteArray.length;
            for (let i = 0; i <= favorite_length - 1; i++) {
                count++;
                favorite_list.innerHTML += `<div class="favorite-list-btn"><div class="favorite-list-info"><div class="favorite-type-icon"><img src="../assets/img/favorite/train.svg"></div><div class="favorite-info"><p>${escapeHtml(favoriteArray[i].name)}<br><span>-${escapeHtml(favoriteArray[i].linename)}-</span></p><div class="favorite-stamemo"><p>${escapeHtml(favoriteArray[i].stamemo)}</p></div></div></div><div class="favorite-list-action"><a href="../station/?stacd=${escapeHtml(favoriteArray[i].code)}" class="favorite-info-open">開く</a><button class="favorite-info-del" onclick="favoriteDel(${escapeHtml(favoriteArray[i].code)})"><img src="../assets/img/favorite/favorite-del.svg"></button></div></div>`;
            }
            if (count < 1) {
                favorite_list.innerHTML += `<div class="none-favorite">お気に入りに登録されている駅がありません</div>`;
            }
        }
    }

    function favoriteDel(code) {
        if (confirm('お気に入りから削除しますか')) {
            const get_favoriteArray = localStorage.getItem('favorite');
            const favoriteArray = JSON.parse(get_favoriteArray);
            const favorite_length = favoriteArray.length;
            for (let i = 0; i <= favorite_length - 1; i++) {
                if (code == favoriteArray[i].code) {
                    favoriteArray.splice(i, 1);
                    window.alert('削除しました。');
                    localStorage.setItem('favorite', JSON.stringify(favoriteArray));
                    location.reload();
                    break;
                }
            }
        }
    }
</script>


</html>