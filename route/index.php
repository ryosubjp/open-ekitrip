<?php
require_once '../manifest/db_config.php';

if (!isset($_GET['linecd'])) {
    echo 'コードが設定されていません。';
    exit;
}

$linecd = htmlspecialchars($_GET['linecd']);
$pdo = new PDO("mysql:host=$db_host;dbname=$db_database;charset=utf8", $db_username, $db_password);
$sql = $pdo->prepare('SELECT * FROM m_line JOIN m_company ON m_line.company_cd = m_company.company_cd where line_cd=?');
$sql->execute([$linecd]);
$row_count = $sql->rowCount();

if ($row_count < 1) {
    echo 'エラーが発生しました';
    exit;
} else {
    foreach ($sql as $row) {
        $line_cd = $row['line_cd'];
        $line_name = $row['line_name'];
        $line_name_h = $row['line_name_h'];
        $line_lon = $row['lon'];
        $line_lat = $row['lat'];
        $line_zoom = $row['zoom'];
        $line_color = $row['line_color_c'];

        if ($line_name_h === $line_name) {
            $line_name = $line_name_h;
        } else {
            $line_name = $line_name_h . '(' . $line_name . ')';
        }

        $company = $row['company_name'];
        $company_url = $row['company_url'];
    }
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

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', 'G-X3VSZBRGMG');
        }
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $line_name; ?>の情報　ekitrip(駅トリップ) - 旅行者のための駅情報と訪問駅記録アプリ</title>
    <meta name="description" content="<?php echo $line_name;
                                        ')' ?>の地図、駅一覧、運営会社が確認できます。　ekitrip(駅トリップ)（ekitrip.com）は、旅行者のための駅と路線情報を提供するサービスです。ekitrip(駅トリップ)は、あなたの旅をより快適で楽しいものにするアプリです。">

    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/station.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/route.css">

    <link rel="apple-touch-icon" sizes="180x180" href="../assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/img/favicon/site.webmanifest">


    <link rel="stylesheet" href="../assets/css/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
</head>

<body>
    <div class="header station-header">
        <?php include '../assets/php/header.php'; ?>
    </div>
    <div id="small_view_map" class="small-view-map"></div>
    <div class="station-info">
        <div class="station-detail">
            <div class="station-title">
                <div class="line-color" style="background-color: #<?php echo $line_color; ?>;"></div>
                <div class="station-name-frame">
                    <h1 class="route-name"><?php echo $line_name; ?></h1>
                </div>
            </div><!--station-title-->
            <div class="info">
                <div class="info-box">
                    <div class="info-title">
                        <img src="../assets/img/station/company.svg">
                        <p>運営会社</p>
                    </div>
                    <div class="company">
                        <div>
                            <p><?php echo $company; ?></p>
                        </div>
                        <div class="company-action">
                            <button onclick="open_company_web()" class="info-btn">
                                <p>Webサイト</p>
                                <img src="../assets/img/station/new_open.svg" width="15px">
                            </button>
                            <!--<a class="info-btn">
                                <p>詳細</p>
                            </a>-->
                        </div>
                    </div>
                </div>
                <div class="info-box">
                    <div class="info-title">
                        <img src="../assets/img/route/route.svg">
                        <p>駅一覧（順不同）</p>
                    </div>
                    <?php
                    $sql = $pdo->prepare('SELECT * FROM m_station where line_cd=?');
                    $sql->execute([$linecd]);
                    $row_count = $sql->rowCount();

                    foreach ($sql as $row) {
                        echo '<a class="route-station-btn" href="../station/?stacd=', $row['station_cd'], '">', $row['station_name'], '<img src="../assets/img/route/right.svg"></a>';
                    }
                    ?>
                </div>
            </div>

            <?php include '../assets/php/footer.php'; ?>
        </div>

    </div>
    <?php include '../assets/php/nav-bar.php'; ?>

    <!--modal-->
    <?php include '../assets/php/fast_access.php'; ?>






    <!---->

</body>
<script src="../assets/js/escape.js"></script>
<script src="../assets/js/settings.js"></script> <!-- 設定の読み込み -->
<script src="../assets/js/modal.js"></script>
<script>
    const lat = <?php echo json_encode($line_lat); ?>;
    const lon = <?php echo json_encode($line_lon); ?>;
    const zoom = <?php echo json_encode($line_zoom); ?>;

    window.onload = function() {

        settings();

        if (settingsData.fase_access_detector === 'yes') {
            const fast_access = document.getElementById('fast_access');
            fast_access.style.display = 'block';
            settingsData.fase_access_detector = 'no';
            localStorage.setItem('sv_settings', JSON.stringify(settingsData));
        }

    }

    //緯度,経度,ズーム
    var map = L.map('small_view_map').setView([lat, lon], zoom);
    // OpenStreetMap から地図画像を読み込む
    L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        maxZoom: 16,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, '
    }).addTo(map);


    function open_company_web() {
        if (confirm('運営会社のWebサイトに移動しますか')) {
            window.open('<?php echo $company_url; ?>');
        }
    }
</script>


</html>