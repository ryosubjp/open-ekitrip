<?php
require_once '../manifest/db_config.php';

if (!isset($_GET['stacd'])) {
    echo 'コードが設定されていません。';
    exit;
}

$stacd = htmlspecialchars($_GET['stacd']);
$pdo = new PDO("mysql:host=$db_host;dbname=$db_database;charset=utf8", $db_username, $db_password);
$sql = $pdo->prepare('SELECT * FROM m_station JOIN m_line ON m_station.line_cd = m_line.line_cd JOIN m_company ON m_line.company_cd = m_company.company_cd /*join m_station_join on m_station_join.station_cd1 = m_station.station_cd*/  where station_cd=?');
$sql->execute([$stacd]);
$row_count = $sql->rowCount();

if ($row_count < 1) {
    echo 'エラーが発生しました';
    exit;
} else {
    foreach ($sql as $row) {
        $station_g_cd = $row['station_g_cd'];
        $station_name = $row['station_name'];
        $line_cd = $row['line_cd'];
        $line_name = $row['line_name'];
        $line_name_h = $row['line_name_h'];

        if ($line_name_h === $line_name) {
            $line_name = $line_name_h;
        } else {
            $line_name = $line_name_h . '(' . $line_name . ')';
        }


        $station_lat = $row['stalat'];
        $station_lon = $row['stalon'];
        $post = $row['post'];
        $address = $row['address'];
        $company = $row['company_name'];
        $company_url = $row['company_url'];
        $open_ymd = $row['open_ymd'];
        if ($row['line_color_c'] === null) {
            $line_color = $row['line_color_c'];
        } else {
            $line_color = 'E8E8E8';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta name=”robots” content=”noindex” />
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
    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/printstyle.css">

    <!--googleFont-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>

    <title>ekitrip.com 駅情報印刷</title>
</head>

<body>
    <div class="print-end">
        <div>
            <h2>印刷が終了したら</h2>
            <button onClick="history.back()">駅ページに戻る</button>
        </div>
    </div>
    <section class=" print-area">
        <?php echo $line_name; ?>
        <h1><?php echo $station_name; ?>駅の情報</h1>
        <div id="small_view_map" class="small-view-map"></div>
        <div class="info">
            <div class="info-box">
                <div class="info-title">
                    <img src="../assets/img/station/transfer.svg">
                    <p>乗り換え</p>
                </div>
                <div>
                    <?php
                    $sql = $pdo->prepare('SELECT * FROM m_station join m_line on m_station.line_cd = m_line.line_cd where station_g_cd=?');
                    $sql->execute([$station_g_cd]);
                    foreach ($sql as $row) {
                        if ($row['line_name'] === $line_name) {
                            continue;
                        } else {
                            echo '<a href="?stacd=', $row['station_cd'], '">
                                    <div class="transfer-btn">
                                        <div>
                                         <div class="transfer-line-color"></div>
                                         <p>', $row['line_name_h'], '</p>
                                        </div>
                                        
                                    </div>
                                  </a>';
                            $found = true; // 結果が見つかった場合にフラグを立てる
                        }
                    }
                    if (!$found) {
                        echo '<p>乗り換え可能な路線がありません</p>';
                    }
                    ?>
                </div>
            </div>
            <div class="info-box">
                <div class="info-title">
                    <img src="../assets/img/station/pin.svg">
                    <p>所在地</p>
                </div>
                <div class="address">
                    <div>
                        <p><?php echo $post; ?></p>
                        <p class="address_text"><?php echo $address; ?></p>
                    </div>

                </div>
            </div>
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

                    </div>
                </div>
            </div>
            <div class="info-box">
                <div class="info-title">
                    <img src="../assets/img/station/calendar.svg">
                    <p>開業年月</p>
                </div>
                <div class="company">
                    <div>
                        <?php
                        if ($open_ymd == '0000-00-00') {
                            echo '<p>情報がありません<p>';
                        } else {
                            echo '<p><?php echo ', $open_ymd, ' ?></p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script>
        window.onload = function() {
            const stacd = <?php echo json_encode($stacd); ?>;
            const lat = <?php echo json_encode($station_lat); ?>;
            const lon = <?php echo json_encode($station_lon); ?>;

            // 緯度,経度,ズーム
            var map = L.map('small_view_map').setView([lat, lon], 17);

            // OpenStreetMap から地図画像を読み込む
            L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                maxZoom: 16,
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'
            }).addTo(map);

            setTimeout(function() {
                window.print();
            }, 1000);
        };
    </script>
</body>

</html>