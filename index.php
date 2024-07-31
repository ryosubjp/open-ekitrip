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
    <title>ekitrip(駅トリップ) - 旅行者のための駅情報と訪問駅記録アプリ</title>
    <meta name="description" content="ekitrip(駅トリップ)（ekitrip.com）は、旅行者のための駅と路線情報を提供するサービスです。全国の駅を検索できます。訪問した駅を簡単に記録し、訪問駅数に応じてランクアップするシステムもあります。駅周辺の天気情報もチェックでき、お気に入りの駅をメモ付きで保存して次の旅行計画に役立てましょう。ekitrip(駅トリップ)は、あなたの旅をより快適で楽しいものにするアプリです。">

    <meta property="og:title" content="ekitrip(駅トリップ) - 旅行者のための駅情報と訪問駅記録アプリ">
    <meta property="og:description" content="ekitrip(駅トリップ)（ekitrip.com）は、旅行者のための駅と路線情報を提供するサービスです。全国の駅を検索できます。訪問した駅を簡単に記録し、訪問駅数に応じてランクアップするシステムもあります。駅周辺の天気情報もチェックでき、お気に入りの駅をメモ付きで保存して次の旅行計画に役立てましょう。ekitrip(駅トリップ)は、あなたの旅をより快適で楽しいものにするアプリです。">
    <meta property="og:image" content="https://ekitrip.com/assets/img/logo/ekitrip-og.png">

    <link rel="stylesheet" type="text/css" href="assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/favorite.css">
    <link rel="stylesheet" type="text/css" href="assets/css/visit.css">
    <link rel="stylesheet" type="text/css" href="assets/css/index.css">

    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/img/favicon/site.webmanifest">



    <!--googleFont-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <!--googleFont logo-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>

</head>

<body>
    <div class="header">
        <a href="#"><img class="header-logo" src="assets/img/logo/elogo.svg"></a>
        <div class="header-right">
        </div>
    </div>
    <div class="hero-back">
        <div class="hero-back-effect"></div>
        <div id="small_view_map" class="small-view-map"></div>
    </div>
    <div class="hero">
        <div class="hero-sv-title">
            <h1><span>旅行で列車使うなら</span><br>ekitrip<span class="com">.com</span></h1>
        </div>
        <img class="hero-img-left" src="assets/img/index/user-hero.png">
        <img class="hero-img-right" src="assets/img/index/user2-hero.png">
        <!--<img class="title-logo" src="assets/img/index/hero.svg">-->
    </div>
    <div class="hero-search">
        <form action="search/" method="post" class="search-input">
            <button class="search-btn" type="button" id="search-button">
                <img class="search-btn-img" src="assets/img/index/search.svg">
            </button>
            <input type="text" name="search" placeholder="駅名・路線名を入力して検索">
        </form>
        <div class="search-option">
            <a data-modal="postsearch" class="openModalBtn search-option-btn search-map" href="#"><img src="assets/img/index/search-map.svg">
                <p>地域から探す</p>
            </a>
            <a class="search-option-btn search-history" href="history/"><img src="assets/img/index/history.svg">
                <p>履歴</p>
            </a>
        </div>
    </div>
    <div class="content-area">
        <!--visit-timeline-->
        <div class="visit-timeline-index" id="visit_timeline_index">
            <div class="visit-title">
                <div>
                    <img src="assets/img/index/features-card-traveler.png">
                    <p>訪問した駅<br>タイムライン</p>
                </div>
                <a href="visit/">訪問履歴を開く→</a>
            </div>
            <div class="visit-timeline" id="visit_timeline"></div>
        </div>
        <!--favorite-list-->
        <div class="favorite-list-index" id="favorite_list_index">
            <div class="favorite-title">
                <div>
                    <img src="assets/img/index/features-card-user.png">
                    <p>保存した駅</p>
                </div>
                <a href="favorite/">保存した駅を開く→</a>
            </div>
            <div class="favorite-list" id="favorite_list"></div>
        </div>
        <!--features-->
        <div class="features-card" id="visit_features">
            <div class="features-card-tag">
                <p>注目の新機能</p>
            </div>
            <img src="assets/img/index/features-card-traveler.png">
            <h2>訪れた駅を記録する</h2>
            <p class="function-exp">駅ページで「訪問履歴に追加」ボタンで履歴に駅を追加できます。また専用のページから訪問した駅の一覧を日付などと一緒に後から確認できます。駅情報ページにも前回訪れた日時などが表示されるようになります。</p>
            <button data-modal="visit_introduction" class="openModalBtn try-function">使ってみる</button>
        </div>
        <div class="features-card" id="features_card">
            <div class="features-card-tag">
                <p>新機能</p>
            </div>
            <img src="assets/img/index/features-card-user.png">
            <h2>お気に入り駅機能</h2>
            <p class="function-exp">よく使う駅やお気に入りの駅の情報をすぐに確認出来るようになるお気に入り駅機能をリリースします。駅を登録する際に、メモを一緒に保存でき、メモには、ユーザーが任意の情報を入力できるため、駅に関する特記事項や予定などを記録できます。たとえば、「この駅で待ち合わせ」といったメモを保存することが可能です。</p>
            <button data-modal="favorite_introduction" class="openModalBtn try-function">使ってみる</button>
        </div>
        <?php include 'assets/php/footer.php'; ?>
    </div>
    <div class="navi-bar">
        <a href="#" class="navi-bar-btn">
            <img src="assets/img/navi/navi_home.svg">
            <p>ホーム</p>
        </a>
        <a href="visit/" class="navi-bar-btn">
            <img src="assets/img/navi/navi_visit.svg">
            <p>訪問した駅</p>
        </a>
        <a href="favorite/" class="navi-bar-btn">
            <img src="assets/img/navi/navi_favorite.svg">
            <p>保存した駅</p>
        </a>
        <a href="menu/" class="navi-bar-btn">
            <img src="assets/img/navi/navi_menu.svg">
            <p>メニュー</p>
        </a>
    </div>

    <!--modal-->
    <?php include 'assets/php/fast_access.php'; ?>

    <div id="postsearch" class="modal">
        <div class="modal-back">
            <div class="modal-content">
                <div class="modal-title">
                    <div>
                        <h2>地域から検索</h2>
                    </div>
                    <img class="close-btn" src="assets/img/station/close.svg">
                </div>
                <form action="search/index.php" method="post">
                    <input class="post-search-input" type="text" name="search-post" placeholder="郵便番号">
                    <button class="post-search-btn">検索</button>
                </form>
            </div>
        </div>
    </div>

    <div id="visit_introduction" class="modal">
        <div class="modal-back">
            <div class="modal-content">
                <div class="modal-title">
                    <div>
                    </div>
                    <img class="close-btn" src="assets/img/station/close.svg">
                </div>
                <div class="intro-title">
                    <img src="assets/img/index/features-card-traveler.png">
                    <h2>訪れた駅を記録する</h2>
                </div>
                <div class="step-card">
                    <p class="step-no">STEP1</p>
                    <h3>記録したい駅を検索</h3>
                    <div class="step">
                        <img width="100px" src="assets/img/index/search_man_color.png">
                    </div>
                    <a href="search/" class="try-function-action">検索する→</a>
                </div>
                <div class="step-card">
                    <p class="step-no">STEP2</p>
                    <h3>「訪問履歴に追加」をタップ</h3>
                    <div class="step">
                        <img width="200px" src="assets/img/index/station-menu-visit.png">
                    </div>
                </div>
                <div class="step-card">
                    <p class="step-no">STEP3</p>
                    <h3>訪問した日付、時刻を入力</h3>
                    <div class="step">
                        <img width="230px" src="assets/img/index/visit-setting-day.png">
                    </div>
                </div>
                <div class="step-card">
                    <p class="step-no">STEP4</p>
                    <h3>コメントや思い出を保存</h3>
                    <div class="step">
                        <img width="230px" src="assets/img/index/visit-comment.png">
                    </div>
                </div>
                <div class="step-card">
                    <p class="step-no">STEP5</p>
                    <h3>タブの「訪問履歴」から閲覧できます</h3>
                    <div class="step">
                        <img width="230px" src="assets/img/index/menu-visit.png">
                    </div>
                    <a href="visit/" class="try-function-action">訪問履歴を開く→</a>
                </div>
            </div>
        </div>
    </div>

    <div id="favorite_introduction" class="modal">
        <div class="modal-back">
            <div class="modal-content">
                <div class="modal-title">
                    <div>
                    </div>
                    <img class="close-btn" src="assets/img/station/close.svg">
                </div>
                <div class="intro-title">
                    <img src="assets/img/index/features-card-user.png">
                    <h2>お気に入り駅保存方法</h2>
                </div>
                <div class="step-card">
                    <p class="step-no">STEP1</p>
                    <h3>保存したい駅を検索</h3>
                    <div class="step">
                        <img width="100px" src="assets/img/index/search_man_color.png">
                    </div>
                    <a href="search/" class="try-function-action">検索する→</a>
                </div>
                <div class="step-card">
                    <p class="step-no">STEP2</p>
                    <h3>「保存」をタップ</h3>
                    <div class="step">
                        <img width="150px" src="assets/img/index/station-menu-favorite.png">
                    </div>
                </div>
                <div class="step-card">
                    <p class="step-no">STEP3</p>
                    <h3>訪問した日付、時刻を入力</h3>
                    <div class="step">
                        <img width="230px" src="assets/img/index/visit-setting-day.png">
                    </div>
                </div>
                <div class="step-card">
                    <p class="step-no">STEP4</p>
                    <h3>メモを保存</h3>
                    <div class="step">
                        <img width="200px" src="assets/img/index/favorite-memo.png">
                    </div>
                </div>
                <div class="step-card">
                    <p class="step-no">STEP5</p>
                    <h3>タブの「保存した駅」から閲覧できます</h3>
                    <div class="step">
                        <img width="200px" src="assets/img/index/menu-favorite.png">
                    </div>
                    <a href="favorite/" class="try-function-action">保存した駅を開く→</a>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="assets/js/escape.js"></script>
<script src="assets/js/settings.js"></script> <!-- 設定の読み込み -->
<script src="assets/js/modal.js"></script>
<script>
    document.getElementById('search-button').addEventListener('click', function() {
        document.querySelector('.search-input').submit();
    });

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

        //setting
        settings();

        if (settingsData.fase_access_detector === 'yes') {
            const fast_access = document.getElementById('fast_access');
            fast_access.style.display = 'block';
            settingsData.fase_access_detector = 'no';
            localStorage.setItem('sv_settings',JSON.stringify(settingsData));
        }


        //visit-index
        const get_visitArray = localStorage.getItem('visit_history');
        const visit_array = JSON.parse(get_visitArray);
        const visit_features = document.getElementById("visit_features");
        const visit_timeline_index = document.getElementById("visit_timeline_index");
        const visit_timeline = document.getElementById('visit_timeline');

        if (visit_array) {
            //visitを使用している
            visit_features.style.display = "none";
            visit_timeline_index.style.display = "block";

            //出力処理
            const visit_length = visit_array.length;
            let loop_day = '';
            for (let i = 0; i <= visit_length - 1; i++) {
                let day = visit_array[i].date.year + visit_array[i].date.month + visit_array[i].date.day;
                if (loop_day !== day) {
                    visit_timeline.innerHTML += `<div class="visit-day"><p>` + escapeHtml(visit_array[i].date.month) + `/` + escapeHtml(visit_array[i].date.day) + `</p></div>`;
                }
                loop_day = visit_array[i].date.year + visit_array[i].date.month + visit_array[i].date.day;
                visit_timeline.innerHTML += `<div class="visit-station"><div class="visit-time"><p>` + escapeHtml(visit_array[i].date.hour) + `:` + escapeHtml(visit_array[i].date.minute) + `</p></div><div class="visit-station-info"><div><p>` + escapeHtml(visit_array[i].linename) + `</p><h3>` + escapeHtml(visit_array[i].name) + `駅</h3><p class="visit-memo">` + escapeHtml(visit_array[i].stamemo) + `</p></div><button onclick="visit_del(${i})"><img src="assets/img/visit/delete.svg"></button></div></div>`;
                if (i < visit_length - 1) {
                    let next_day = visit_array[i + 1].date.year + visit_array[i + 1].date.month + visit_array[i + 1].date.day;
                    if (next_day == loop_day) {
                        visit_timeline.innerHTML += `<div class="visit-timeline-line"></div>`;
                    }
                }
            }
        } else {
            visit_timeline_index.style.display = "none";
            visit_features.style.display = "block";
        }

        //favorite-index
        const get_favoriteArray = localStorage.getItem('favorite');
        const favoriteArray = JSON.parse(get_favoriteArray);
        const features_card = document.getElementById('features_card');
        const favorite_list_index = document.getElementById('favorite_list_index');
        const favorite_list = document.getElementById('favorite_list');

        if (favoriteArray) {
            //favoriteを使用している
            features_card.style.display = "none";
            favorite_list_index.style.display = "block";
            const favorite_length = favoriteArray.length;
            for (let i = 0; i <= favorite_length - 1; i++) {
                favorite_list.innerHTML += `<div class="favorite-list-btn"><div class="favorite-list-info"><div class="favorite-type-icon"><img src="assets/img/favorite/train.svg"></div><div class="favorite-info"><p>` + escapeHtml(favoriteArray[i].name) + `<span> -` + escapeHtml(favoriteArray[i].linename) + `-</span></p><div class="favorite-stamemo"><p>` + escapeHtml(favoriteArray[i].stamemo) + `</p></div></div></div><a href="station/?stacd=` + escapeHtml(favoriteArray[i].code) + `" class="favorite-info-open">開く</a></div>`;
            }
        } else {
            features_card.style.display = "block";
            favorite_list_index.style.display = "none";
        }
    }



    function visit_del(code) {
        const get_visitArray = localStorage.getItem('visit_history');
        const visit_array = JSON.parse(get_visitArray);
        if (confirm('訪問履歴から削除しますか？')) {
            visit_array.splice(code, 1);
            localStorage.setItem('visit_history', JSON.stringify(visit_array));
            window.alert('削除しました');
            location.reload();
        }

    }
</script>


</html>