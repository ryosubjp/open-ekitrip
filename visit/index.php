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
    <title>ekitrip(駅トリップ) 訪問駅記録- 旅行者のための駅情報と訪問駅記録アプリ</title>
    <meta name="description" content="ekitrip(駅トリップ)（ekitrip.com）の訪問駅履歴機能で、旅行や出張で訪問して駅を簡単に記録・管理！「訪問履歴に追加」ボタンでお気に入りの駅を追加し、専用ページで一覧と日付を確認できます。駅情報ページには前回訪問日時も表示。また訪問駅数に応じてランクアップしていきます！ekitrip(駅トリップ)で訪問履歴を一目で把握し、旅行の思い出を記録しましょう">


    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">

    <link rel="stylesheet" type="text/css" href="../assets/css/index.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/visit.css">

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
        <div class="visit-card" id="visit_card">
            <div class="visit-card-userinfo">
                <p id="user_name"></p>
                <p id="user_level"></p>
            </div>
            <div class="visit-station-count">
                <p>あなたは</p>
                <p class="visit-station-count-disp" id="visit_station_count_disp"></p>
                <p>に訪問しました。</p>
            </div>
        </div>
    </div>
    <div class="content-area">
        <div class="visit-level">
            <img class="visit-level-gage" id="level_gage_img" src="../assets/img/visit/level-gage-none.svg">
        </div>
        <div class="browser-alert" id="browser_alert">
            <img src="../assets/img/error/warning.svg" width="40px">
            <div>
                <p>非推奨ブラウザです</p>
                <p>この機能を使用する場合デフォルトブラウザ（SafariやChrome）などで開いてください。</p>
                <button onclick="BA_copyButton('https://ekitrip.com/visit/')">リンクコピー</button>
            </div>
        </div>
        <!--visit-timeline-->
        <div class="visit-timeline" id="visit_timeline"></div>
        <?php include '../assets/php/footer.php'; ?>
    </div>
    <?php include '../assets/php/nav-bar.php'; ?>

    <!--modal-->
    <?php include '../assets/php/fast_access.php'; ?>

    <div id="visit_info_modal" class="modal">
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
</body>
<script src="../assets/js/escape.js"></script>
<script src="../assets/js/modal.js"></script>
<script src="../assets/js/browser_judge.js"></script>
<script src="../assets/js/settings.js"></script> <!-- 設定の読み込み -->
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

    const get_visitArray = localStorage.getItem('visit_history');
    const visit_array = JSON.parse(get_visitArray);

    window.onload = function() {

        settings();
        browser_judge();

        if (settingsData.fase_access_detector === 'yes') {
            const fast_access = document.getElementById('fast_access');
            fast_access.style.display = 'block';
            settingsData.fase_access_detector = 'no';
            localStorage.setItem('sv_settings', JSON.stringify(settingsData));
        }

        //visit

        if (!settingsData.userAchievement) {
            const setAchievement = {
                "visit_level": "",
            }
            settingsData = {
                ...settingsData,
                ...setAchievement
            };
            localStorage.setItem('sv_settings', JSON.stringify(settingsData));
        }


        const visit_timeline = document.getElementById('visit_timeline');
        const user_name_disp = document.getElementById('user_name');
        const user_level = document.getElementById('user_level');
        const visit_card = document.getElementById('visit_card');
        const level_gage_img = document.getElementById('level_gage_img');
        const visit_station_count_disp = document.getElementById('visit_station_count_disp');

        user_name_disp.innerText += settingsData.user_name + `さん`;
        if (visit_array) {
            //visitを使用している
            // Setを使ってcodeの重複を排除
            const uniqueCodes = new Set(visit_array.map(visit_array => visit_array.g_code));
            // 重複を排除したcodeの数を取得
            const visit_station_count = uniqueCodes.size;
            if (visit_station_count >= 1) {
                visit_station_count_disp.innerText += `${escapeHtml(visit_station_count)}駅`;
                if (visit_station_count < 20) {
                    //初級
                    visit_card.style.background = `linear-gradient(0deg, rgba(76,175,80,1) 0%, rgba(165,214,167,1) 100%)`;
                    level_gage_img.src = `../assets/img/visit/level-gage-Novice.svg`;
                    user_level.innerText = `初級者`;
                    settingsData.visit_level = "Novice";
                } else if (visit_station_count < 40) {
                    //探検
                    visit_card.style.background = `linear-gradient(0deg, rgba(33,150,243,1) 0%, rgba(144,202,249,1) 100%)`;
                    level_gage_img.src = `../assets/img/visit/level-gage-Explorer.svg`;
                    user_level.innerText = `探検者`;
                    settingsData.visit_level = "Explorer";
                } else if (visit_station_count < 100) {
                    //中級
                    visit_card.style.background = `linear-gradient(0deg, rgba(255,152,0,1) 0%, rgba(255,204,128,1) 100%)`;
                    level_gage_img.src = `../assets/img/visit/level-gage-Intermediate.svg`;
                    user_level.innerText = `中級者`;
                    settingsData.visit_level = "Intermediate";
                } else if (visit_station_count < 500) {
                    //熟練
                    visit_card.style.background = `linear-gradient(0deg, rgba(156,39,176,1) 0%, rgba(206,147,216,1) 100%)`;
                    level_gage_img.src = `../assets/img/visit/level-gage-Expert.svg`;
                    user_level.innerText = `熟練者`;
                    settingsData.visit_level = "Expert";
                } else {
                    //マスター
                    visit_card.style.background = `linear-gradient(0deg, rgba(255,215,0,1) 0%, rgba(255,240,105,1) 100%)`;
                    level_gage_img.src = `../assets/img/visit/level-gage-Master.svg`;
                    user_level.innerText = `マスター`;
                    settingsData.visit_level = "Master";
                }
            } else {
                visit_station_count_disp.innerHTML += `0駅`;
            }

            localStorage.setItem('sv_settings', JSON.stringify(settingsData));


            //出力処理
            const visit_length = visit_array.length;
            let loop_day = '';
            for (let i = 0; i <= visit_length - 1; i++) {
                let day = visit_array[i].date.year + visit_array[i].date.month + visit_array[i].date.day;
                if (loop_day !== day) {
                    visit_timeline.innerHTML += `<div class="visit-day"><p>` + escapeHtml(visit_array[i].date.month) + `/` + escapeHtml(visit_array[i].date.day) + `</p></div>`;
                }
                loop_day = visit_array[i].date.year + visit_array[i].date.month + visit_array[i].date.day;
                visit_timeline.innerHTML += `<div class="visit-station"><div class="visit-time"><p>` + escapeHtml(visit_array[i].date.hour) + `:` + escapeHtml(visit_array[i].date.minute) + `</p></div><div class="visit-station-info"><div><p>` + escapeHtml(visit_array[i].linename) + `</p><h3>` + escapeHtml(visit_array[i].name) + `駅</h3><p class="visit-memo">` + escapeHtml(visit_array[i].stamemo) + `</p></div><button onclick="visit_del(${i})"><img src="../assets/img/visit/delete.svg"></button></div></div>`;
                if (i < visit_length - 1) {
                    let next_day = visit_array[i + 1].date.year + visit_array[i + 1].date.month + visit_array[i + 1].date.day;
                    if (next_day == loop_day) {
                        visit_timeline.innerHTML += `<div class="visit-timeline-line"></div>`;
                    }
                }

            }
        } else {
            visit_station_count_disp.innerText += `0駅`;
            visit_timeline.innerHTML += `<div class="visit-history-none"><img src="../assets/img/index/features-card-traveler.png"><h1>あなたの訪問した駅を記録</h1><p>駅ページで「訪問履歴に追加」ボタンで履歴に駅を追加できます。また専用のページから訪問した駅の一覧を日付などと一緒に後から確認できます。駅情報ページにも前回訪れた日時などが表示されるようになります。</p></div>`;
        }
    }

    function openVisitInfo(code) {
        location.href = 'visitinfo.php/?visitcd=' + code;
    }

    function visit_del(code) {
        if (confirm('訪問履歴から削除しますか？')) {
            visit_array.splice(code, 1);
            localStorage.setItem('visit_history', JSON.stringify(visit_array));
            window.alert('削除しました');
            location.reload();
        }

    }
</script>


</html>