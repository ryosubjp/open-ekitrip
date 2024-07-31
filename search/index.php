<?php
if (!isset($_POST['search']) and !isset($_POST['search-post']) or empty($_POST['search']) and empty($_POST['search-post'])) {
    header('Location: ../index.php');
    exit;
}
?>
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
    <title>ekitrip(駅トリップ) 全国の駅・路線を検索- 旅行者のための駅情報と訪問駅記録アプリ</title>
    <meta name="description" content="ekitrip(駅トリップ)（ekitrip.com）で、ひらがな・カタカナによる全国の駅・路線の検索が可能！郵便番号からの検索も実現。駅周辺の天気や路線カラー表示で、乗り換えがスムーズに。訪問履歴機能で旅行の記録を簡単に、保存機能でお気に入りの駅情報もすぐに確認できます。ekitrip(駅トリップ)で快適な旅行をサポートします！">

    

    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/index.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/search.css">

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
        <img class="title-logo" src="../assets/img/index/hero.svg">
    </div>
    <div class="hero-search">
        <form action="#" method="post" class="search-input">
            <button class="search-btn" type="button" id="search-button">
                <img class="search-btn-img" src="../assets/img/index/search.svg">
            </button>
            <input type="text" name="search" placeholder="駅名・路線名を入力して検索">
        </form>
        <div class="search-option">
            <a data-modal="postsearch" class="openModalBtn search-option-btn search-map" href="#"><img src="../assets/img/index/search-map.svg">
                <p>地域から探す</p>
            </a>
            <a class="search-option-btn search-history" href="../history/"><img src="../assets/img/index/history.svg">
                <p>履歴</p>
            </a>
        </div>
    </div>
    <div class="content-area">
        <?php
        require_once '../manifest/db_config.php';
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_database;charset=utf8", $db_username, $db_password);
        if (isset($_POST['search'])) {
            // 「○○駅」が入力された場合に、「○○」と「駅」に分割
            if (preg_match('/^(.+)駅$/', htmlspecialchars($_POST['search']), $matches)) {
                $search = $matches[1];
            } else {
                $search = htmlspecialchars($_POST['search']);
            }

            echo '<h3 class="result-title">「', $search, '」の検索結果</h3>';

            $searchTerm = "%$search%"; // 検索キーワードを指定

            $sql = $pdo->prepare("SELECT * FROM m_station join m_line on m_station.line_cd = m_line.line_cd WHERE station_name LIKE ? OR station_name_k LIKE ? OR station_name_r LIKE ?");
            $sql->execute([$searchTerm, $searchTerm, $searchTerm]);

            foreach ($sql as $row) {
                echo '<div class="result-btn">';
                echo '<div class="result-info">';
                echo '<div class="result-type-icon" ><img src="../assets/img/search/train.svg"></div>';
                echo '<div>';
                if ($row['line_name'] === $row['line_name_h']) {
                    echo '<p>', $row['line_name'], '</p>';
                } else {
                    echo '<p>', $row['line_name_h'], '(', $row['line_name'], ')</p>';
                }
                echo '<h3>', $row['station_name'], '駅</h3>';
                echo '</div>';
                echo '</div>';
                echo '<a href="../station/?stacd=', $row['station_cd'], '">開く</a>';
                echo '</div>';
            }


            $route_sql = $pdo->prepare("SELECT * FROM `m_line` WHERE `line_name` LIKE ? OR `line_name_h` LIKE ?");
            $route_sql->execute([$searchTerm, $searchTerm]);

            echo '<h3>路線</h3>';
            foreach ($route_sql as $row) {
                echo '<div class="result-btn">';
                echo '<div class="result-info">';
                echo '<div class="result-type-icon"><img src="../assets/img/search/train.svg"></div>';
                echo '<div>';
                if ($row['line_name'] === $row['line_name_h']) {
                    echo '<p>', $row['line_name'], '</p>';
                } else {
                    echo '<p>', $row['line_name_h'], '(', $row['line_name'], ')</p>';
                }
                echo '</div>';
                echo '</div>';
                echo '<a href="../route/?linecd=', $row['line_cd'], '">開く</a>';
                echo '</div>';
            }
        }
        if (isset($_POST['search-post'])) {
            $search = htmlspecialchars($_POST['search-post']);

            $cleanedPostalCode = preg_replace('/\D/', '', $search);

            // 7桁の数字であることを確認
            if (strlen($cleanedPostalCode) == 7) {
                // 3桁-4桁に分割
                $part1 = substr($cleanedPostalCode, 0, 3);
                $part2 = substr($cleanedPostalCode, 3, 4);

                $post_address = $part1 . '-' . $part2;
            } else {
                echo '桁数エラー';
            }

            echo '<h3 class="result-title">「郵便番号：', $post_address, '」の検索結果</h3>';

            $sql = $pdo->prepare("SELECT * FROM m_station join m_line on m_station.line_cd = m_line.line_cd WHERE post = ?");
            $sql->execute([$post_address]);

            foreach ($sql as $row) {
                echo '<div class="result-btn">';
                echo '<div class="result-info">';
                echo '<div class="result-type-icon"><img src="../assets/img/search/train.svg"></div>';
                echo '<div>';
                if ($row['line_name'] === $row['line_name_h']) {
                    echo '<p>', $row['line_name'], '</p>';
                } else {
                    echo '<p>', $row['line_name_h'], '(', $row['line_name'], ')</p>';
                }
                echo '<h3>', $row['station_name'], '駅</h3>';
                echo '</div>';
                echo '</div>';
                echo '<a href="../station/?stacd=', $row['station_cd'], '">開く</a>';
                echo '</div>';
            }
        }
        ?>
        <?php include '../assets/php/footer.php'; ?>
    </div>
    <?php include '../assets/php/nav-bar.php'; ?>

    <div id="postsearch" class="modal">
        <div class="modal-back">
            <div class="modal-content">
                <div class="modal-title">
                    <div>
                        <h2>地域から検索</h2>
                    </div>
                    <img class="close-btn" src="../assets/img/station/close.svg">
                </div>
                <form action="#" method="post">
                    <input class="post-search-input" type="text" name="search-post" placeholder="郵便番号">
                    <button class="post-search-btn">検索</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="../assets/js/modal.js"></script>
<script src="../assets/js/settings.js"></script> <!-- 設定の読み込み -->
<script>
    window.onload = function() {
        settings();
    }
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
</script>

</html>