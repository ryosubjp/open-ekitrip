<?php

if (!isset($_GET['stacd'])) {
    echo 'コードが設定されていません。';
    exit;
}


if (!ctype_digit($_GET['stacd'])) {
    header('Location: ../index.php');
    exit;
}


require_once '../manifest/db_config.php';
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
        if ($row['line_color_c'] === '') {
            $line_color = 'E8E8E8';
        } else {
            $line_color = $row['line_color_c'];
        }
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
    <title><?php echo $station_name; ?>駅の地図、周辺天気、乗り換え路線 <?php echo $line_name; ?> ekitrip.com - 旅行者のための駅情報と訪問駅記録アプリ</title>
    <meta name="description" content="<?php echo $station_name, '(', $line_name, ')' ?>駅の地図、周辺天気、乗り換え路線、住所、運営会社、開業年月が確認できます。他にも<?php echo $station_name; ?>駅の時刻表を検索できます。ekitrip(駅トリップ)（ekitrip.com）は、旅行者のための駅と路線情報を提供するサービスです。ekitrip(駅トリップ)は、あなたの旅をより快適で楽しいものにするアプリです。">

    <meta property="og:title" content="<?php echo $station_name, '(', $line_name, ')'; ?>駅の情報　ekitrip.com - 旅行者のための駅情報と訪問駅記録アプリ">
    <meta property="og:description" content="<?php echo $station_name, '(', $line_name, ')' ?>駅の地図、周辺天気、乗り換え路線、住所、運営会社、開業年月が確認できます。他にも<?php echo $station_name; ?>駅の時刻表を検索できます。ekitrip(駅トリップ)（ekitrip.com）は、旅行者のための駅と路線情報を提供するサービスです。ekitrip(駅トリップ)は、あなたの旅をより快適で楽しいものにするアプリです。">
    <meta property="og:image" content="https://ekitrip.com/assets/img/logo/ekitrip-og.png">

    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/station.css">

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

    <?php
    $sql = $pdo->prepare('SELECT * FROM m_station_join join m_station on m_station_join.station_cd1 = m_station.station_cd where station_cd2=?');
    $sql->execute([$stacd]);
    foreach ($sql as $row) {
        $nextsta1 = $row['station_cd1'];
        $nextsta1_n = $row['station_name'];
    }
    $sql = $pdo->prepare('SELECT * FROM m_station_join join m_station on m_station_join.station_cd2 = m_station.station_cd  where station_cd1=?');
    $sql->execute([$stacd]);
    foreach ($sql as $row) {
        $nextsta2 = $row['station_cd2'];
        $nextsta2_n = $row['station_name'];
    }
    ?>
    <div class="header station-header">
        <?php include '../assets/php/header.php'; ?>
    </div>
    <div id="small_view_map" class="small-view-map"></div>
    <div class="station-info">
        <div class="adjacent-station left">
            <?php if (!empty($nextsta1)) : ?>
                <a href="../station/?stacd=<?php echo $nextsta1; ?>" class="adjacent-station-btn">
                    <img src="../assets/img/station/adjacent-station_left.svg">
                    <p><?php echo $nextsta1_n; ?></p>
                </a>
            <?php endif; ?>
            <?php if (!empty($nextsta2)) : ?>
                <a href="../station/?stacd=<?php echo $nextsta2; ?>" class="adjacent-station-btn">
                    <p><?php echo $nextsta2_n; ?></p>
                    <img src="../assets/img/station/adjacent-station_right.svg">
                </a>
            <?php endif; ?>
        </div>
        <div class="station-detail">
            <div class="station-title">
                <div class="line-color" style="background-color: #<?php echo $line_color; ?>;"></div>
                <div class="station-name-frame">
                    <h1><?php echo $station_name; ?>駅</h1>
                    <div class="line-info">
                        <?php echo $line_name; ?>
                        <a href="../route/?linecd=<?php echo $line_cd; ?>">路線詳細</a>
                    </div>
                </div>
            </div><!--station-title-->
            <div class="station-menu">
                <a data-modal="add_visit_modal" class="openModalBtn station-menu-btn visit-menubtn">
                    <img src="../assets/img/station/station-menu/add_visit.svg">
                    <p>訪問履歴に追加</p>
                </a>
                <button data-modal="add_favorite_modal" class="openModalBtn station-menu-btn">
                    <img src="../assets/img/station/station-menu/favorite.svg">
                    <p>保存</p>
                </button>
                <button data-modal="menu_share_modal" class="openModalBtn station-menu-btn">
                    <img src="../assets/img/station/station-menu/share.svg">
                    <p>共有</p>
                </button>
                <button data-modal="menu_more_modal" class="openModalBtn station-menu-btn more-btn">
                    <img src="../assets/img/station/station-menu/menu-more.svg">
                    <p>その他</p>
                </button>
            </div><!--station-menu-->
            <div class="station-widget">
                <div class="widget-frame visit-widget">
                    <div class="visit-content" id="visit_content">
                        <div class="visit-intro">
                            <img src="../assets/img/station/widget/sta_widget_visit.svg">
                            <div>
                                <h3>訪問履歴に追加しましょう</h3>
                                <p>追加すると前回訪れた日付が表示されます。</p>
                            </div>
                        </div>
                        <div class="visit-history-action">
                            <a href="../visit/" class="visit-history-btn">履歴を開く</a>
                        </div>
                    </div>
                </div>
                <div class="widget-frame weather-widget">
                    <h3>駅周辺の天気（現在）</h3>
                    <div id="weather"></div>
                </div>
            </div><!--station-widget-->
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
                                                 <div class="transfer-line-color" style="background-color: #', $row['line_color_c'], '"></div>
                                                 <p>', $row['line_name_h'], '</p>
                                                </div>
                                                <img src="../assets/img/station/right-btn.svg">
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
                        <button id="copyAddressButton" class="info-btn address-copy-btn">
                            <img src="../assets/img/station/copy.svg">
                            <p>コピー</p>
                        </button>
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
            <?php include '../assets/php/footer.php'; ?>
        </div>

    </div>
    <?php include '../assets/php/nav-bar.php'; ?>

    <!--modal-->
    <?php include '../assets/php/fast_access.php'; ?>

    <div id="menu_more_modal" class="modal">
        <div class="modal-back">
            <div class="modal-content">
                <div class="modal-title">
                    <div></div>
                    <img class="close-btn" src="../assets/img/station/close.svg">
                </div>
                <div class="more-menu-list">
                    <button data-modal="print_style_modal" class="openModalBtn more-menu-btn">
                        <div>
                            <img src="../assets/img/station/station-menu/print.svg">
                            <p>駅情報を印刷</p>
                        </div>
                        <img src="../assets/img/station/right-btn.svg">
                    </button>
                    <a href="https://www.openstreetmap.org/#map=16/<?php echo $station_lat; ?>/<?php echo $station_lon; ?>" class="more-menu-btn">
                        <div>
                            <img src="../assets/img/station/station-menu/map.svg">
                            <p>駅周辺地図を開く</p>
                        </div>
                        <img src="../assets/img/station/new_open.svg" width="18px">
                    </a>
                    <a href="https://www.google.com/search?q=<?php echo $line_name; ?> <?php echo $station_name; ?>駅の時刻表" class="more-menu-btn">
                        <div>
                            <img src="../assets/img/station/station-menu/time.svg">
                            <p>時刻表をGoogleで検索</p>
                        </div>
                        <img src="../assets/img/station/new_open.svg" width="18px">
                    </a>
                    <a href="#" class="more-menu-btn">
                        <div>
                            <img src="../assets/img/station/station-menu/info.svg">
                            <p>情報更新日</p>
                        </div>
                        <p>2024/7</p>
                    </a>
                </div>
                <div class="more-menu-notices">
                    <p>・当サービスは、当サイトにコンテンツを掲載するにあたって、その内容、機能等について細心の注意を払っておりますが、コンテンツの内容が正確であるかどうか、最新のものであるかどうか、安全なものであるか等について保証をするものではなく、何らの責任を負うものではありません。駅や路線のデータは、駅データ.jpのデータを使用しています。<br>
                        ・「駅周辺の地図を開く」ではOpenStreetMapにアクセスします。<br>
                        © OpenStreetMap contributors<br>
                        ・ Google および Google ロゴは、Google LLC の商標です。</p>
                </div>
            </div><!--modal-content-->
        </div>
    </div>

    <div id="menu_share_modal" class="modal">
        <div class="modal-back">
            <div class="modal-content">
                <div class="modal-title">
                    <div>
                        <h2><?php echo $station_name; ?>駅を共有</h2>
                    </div>
                    <img class="close-btn" src="../assets/img/station/close.svg">
                </div>
                <div class="url-copy">
                    <div class="url-text">
                        <p>https://ekitrip.com/station/?stacd=<?php echo $stacd; ?></p>
                    </div>
                    <button class="copy-url-button" onclick="copy_url_btn()">コピー</button>
                </div>
                <div class="share-option">
                    <a href="#" id="line-share-btn" class="share-option-btn">
                        <img src="../assets/img/station/share/LINE_icon.png" alt="LINEで送る">
                        <p>LINEで送る</p>
                    </a>
                    <a href="mailto:?body=<?php echo $station_name, '駅の情報 ', urlencode('https://ekitrip.com/station/?stacd=' . $stacd); ?>" class="share-option-btn">
                        <img src="../assets/img/station/share/email.svg" alt="メールで送信">
                        <p>メールで送信</p>
                    </a>
                    <a href="http://x.com/share?url=<?php echo urlencode('https://ekitrip.com/station/?stacd=' . $stacd); ?>&text=<?php echo $station_name; ?>駅の情報" class="share-option-btn">
                        <img src="../assets/img/station/share/x.svg" alt="Xで共有">
                        <p>xで共有</p>
                    </a>
                </div>
                <div class="share-with-comment">
                    <div>
                        <h3>まもなく登場</h3>
                        <h2>コメントと共有</h2>
                    </div>
                    <p>この機能では、あなたが入力したコメントを駅情報とともに共有した相手だけに表示できる機能です。コメントの内容はあなたが共有したリンクを知っている人のみが見ることができ、任意の情報を入力できるため、駅に関する特記事項や予定などと一緒に共有できます。たとえば、「この駅で待ち合わせ」といったコメントと共有することが可能です。</p>
                </div>
            </div><!--modal-content-->
        </div>
    </div>

    <div id="add_favorite_modal" class="modal">
        <div class="modal-back">
            <div class="modal-content">
                <div class="modal-title">
                    <div>
                    </div>
                    <img class="close-btn" src="../assets/img/station/close.svg">
                </div>
                <div class="add-station-title">
                    <img src="../assets/img/station/add-user.png">
                    <h5><?php echo $line_name; ?></h5>
                    <h3><?php echo $station_name; ?>駅</h3>
                    <p>をお気に入りに保存しますか？
                    </p>
                </div>
                <div class="add-station-action">
                    <div class="add-memo">
                        <div class="add-memo-title">
                            <div>
                                <img src="../assets/img/station/add-memo.svg">
                                <p class="action-title-text">メモを追加</p>
                            </div>
                            <p class="add-memo-exp">メモには、ユーザーが任意の情報を入力できるため、駅に関する特記事項や予定などを記録できます。たとえば、「旅行２日目で行く観光地の最寄り駅」といったメモを保存することが可能です。</p>
                        </div>
                        <textarea class="fav-sta-memo" name="fav-sta-memo" id="fav-sta-memo-input" placeholder="メモを入力"></textarea>
                    </div>
                    <button class="add-station-btn" onclick="addFavoriteBtn()">お気に入りに追加</button>
                </div>
            </div>
        </div>
    </div>

    <div id="add_visit_modal" class="modal">
        <div class="modal-back">
            <div class="modal-content">
                <div class="modal-title">
                    <div>
                    </div>
                    <img class="close-btn" src="../assets/img/station/close.svg">
                </div>
                <div class="add-station-title">
                    <img src="../assets/img/station/add-user.png">
                    <h5><?php echo $line_name; ?></h5>
                    <h3><?php echo $station_name; ?>駅</h3>
                    <p>を訪問履歴に保存しますか？
                    </p>
                </div>
                <div class="add-station-action">
                    <h3>訪問日時の設定</h3>
                    <?php
                    // 現在の日時を取得
                    $currentDateTime = new DateTime();

                    // 各要素を変数に格納
                    $year = $currentDateTime->format('Y');
                    $month = $currentDateTime->format('m');
                    $day = $currentDateTime->format('d');
                    $hour = $currentDateTime->format('H');
                    $minute = $currentDateTime->format('i');
                    ?>
                    <div class="date">
                        <select name="year" id="year">
                            <?php
                            for ($y = 2000; $y <= $year; $y++) {
                                if ($y == $year) {
                                    echo '<option value="', $y, '" selected>', $y, '</option>';
                                } else {
                                    echo '<option value="', $y, '">', $y, '</option>';
                                }
                            }
                            ?>
                        </select>
                        年
                        <select name="month" id="month">
                            <?php
                            for ($m = 1; $m <= 12; $m++) {
                                if ($m == $month) {
                                    echo '<option value="', $m, '" selected>', $m, '</option>';
                                } else {
                                    echo '<option value="', $m, '">', $m, '</option>';
                                }
                            }
                            ?>
                        </select>
                        月
                        <select name="day" id="day">
                            <?php
                            for ($d = 1; $d <= 31; $d++) {
                                if ($d == $day) {
                                    echo '<option value="', $d, '" selected>', $d, '</option>';
                                } else {
                                    echo '<option value="', $d, '">', $d, '</option>';
                                }
                            }
                            ?>
                        </select>
                        日
                    </div>
                    <div class="hm">
                        <select name="hour" id="hour">
                            <?php
                            for ($h = 0; $h <= 23; $h++) {
                                if ($h == $hour) {
                                    echo '<option value="', $h, '" selected>', $h, '</option>';
                                } else {
                                    echo '<option value="', $h, '">', $h, '</option>';
                                }
                            }
                            ?>
                        </select>
                        時
                        <select name="minute" id="minute">
                            <?php
                            for ($m = 0; $m <= 59; $m++) {
                                $formattedMinute = sprintf('%02d', $m);
                                if ($m == $minute) {
                                    echo '<option value="', $formattedMinute, '" selected>', $formattedMinute, '</option>';
                                } else {
                                    echo '<option value="', $formattedMinute, '">', $formattedMinute, '</option>';
                                }
                            }
                            ?>
                        </select>
                        分
                    </div>
                    <div class="add-comment">
                        <div class="add-comment-title">
                            <div>
                                <img src="../assets/img/station/add-memo.svg">
                                <p class="action-title-text">コメント・思い出を追加</p>
                            </div>
                            <p class="add-comment-exp">訪問情報と一緒にコメントや思い出を追加で保存できます</p>
                        </div>
                        <textarea type="text" class="visit-sta-memo" id="visit-sta-memo-inout" placeholder="コメント・思い出を入力"></textarea>
                    </div>
                    <button class="add-station-btn" onclick="addVisitBtn()">訪問登録</button>
                </div>
            </div>
        </div>
    </div>


    <div id="print_style_modal" class="modal">
        <div class="modal-back">
            <div class="modal-content">
                <div class="modal-title">
                    <div>
                        <p>印刷スタイルを選択</p>
                    </div>
                    <img class="close-btn" src="../assets/img/station/close.svg">
                </div>
                <a href="printstyle-nomal.php?stacd=<?php echo $stacd; ?>" class="print-style-select">
                    <div>
                        <img class="print-style-image" src="../assets/img/station/printstyle/nomal.svg">
                        <p>地図＋駅情報</p>
                    </div>
                    <img src="../assets/img/station/right-btn.svg">
                </a>
                <a href="printstyle-info.php?stacd=<?php echo $stacd; ?>" class="print-style-select">
                    <div>
                        <img class="print-style-image" src="../assets/img/station/printstyle/info.svg">
                        <p>駅情報のみ</p>
                    </div>
                    <img src="../assets/img/station/right-btn.svg">
                </a>
                <a href="printstyle-map.php?stacd=<?php echo $stacd; ?>" class="print-style-select">
                    <div>
                        <img class="print-style-image" src="../assets/img/station/printstyle/map.svg">
                        <p>地図のみ（駅名あり）</p>
                    </div>
                    <img src="../assets/img/station/right-btn.svg">
                </a>
                <div>
                    <p style=" padding-top:10px; font-size:13px; color: #ffcc00;">ご注意：Safariを使用している場合「自動的に印刷することは禁止されています」と表示される場合がありますが「許可」をタップしてください。</p>
                </div>
            </div>
        </div>
    </div>

    <!---->

</body>
<script src="../assets/js/escape.js"></script>
<script src="../assets/js/settings.js"></script> <!-- 設定の読み込み -->
<script src="../assets/js/modal.js"></script>
<script src="../assets/js/weather.js"></script>
<script>
    const stacd = <?php echo json_encode($stacd); ?>;
    const lat = <?php echo json_encode($station_lat); ?>;
    const lon = <?php echo json_encode($station_lon); ?>;
    getWeather(lat, lon);

    window.onload = function() {

        settings();

        if (settingsData.fase_access_detector === 'yes') {
            const fast_access = document.getElementById('fast_access');
            fast_access.style.display = 'block';
            settingsData.fase_access_detector = 'no';
            localStorage.setItem('sv_settings', JSON.stringify(settingsData));
        }

        //visitdisp
        const getArray = localStorage.getItem('visit_history');
        if (getArray) {
            const visitData = JSON.parse(getArray);
            const visit_widget = document.getElementById('visit_content');
            let last_visit_year = '';
            let last_visit_month = '';
            let last_visit_day = '';
            let count = 0;
            for (let i = 0; i < visitData.length; i++) {
                if (visitData[i].code == stacd) {
                    count++;

                    if (count <= 1) {
                        last_visit_year = escapeHtml(visitData[i].date.year);
                        last_visit_month = escapeHtml(visitData[i].date.month);
                        last_visit_day = escapeHtml(visitData[i].date.day);
                    }
                }
            }
            if (count >= 1) {
                visit_widget.innerHTML = `<div class="visit_history"><img src="../assets/img/station/widget/sta_widget_visit.svg"><div><h3>この駅には${escapeHtml(count)}回訪問しました。</h3><p>前回は${escapeHtml(last_visit_month)}月${escapeHtml(last_visit_day)}日にこの駅を訪問しました。</p></div>`;
            } else {
                visit_widget.innerHTML = `<div class="visit-intro"><img src="../assets/img/station/widget/sta_widget_visit.svg"><div><h3>訪問履歴に追加しましょう</h3><p>追加すると前回訪れた日付が表示されます。</p></div></div>`;
            }
        }

        //history
        if (settingsData.function_switch.fn_se5 === 'on') {
            const addHistory = {
                type: "station",
                code: escapeHtml(<?php echo json_encode($stacd); ?>),
                name: escapeHtml(<?php echo json_encode($station_name); ?>),
                linecd: escapeHtml(<?php echo json_encode($line_cd); ?>),
                linename: escapeHtml(<?php echo json_encode($line_name); ?>),
                linecolor: escapeHtml(<?php echo json_encode($line_color); ?>)
            };

            const get_history = localStorage.getItem('history');
            let historyData = [];
            if (get_history) {
                historyData = JSON.parse(get_history);
                if (historyData.length >= 50) {
                    historyData.shift();
                }
                historyData.push(addHistory);
            } else {
                historyData.push(addHistory);
            }
            localStorage.setItem('history', JSON.stringify(historyData));
        } else {
            console.log('履歴機能はOFFになっています。');
        }
    }

    //緯度,経度,ズーム
    var map = L.map('small_view_map').setView([lat, lon], 17);
    // OpenStreetMap から地図画像を読み込む
    L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        maxZoom: 16,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, '
    }).addTo(map);

    document.getElementById('copyAddressButton').addEventListener('click', function() {
        // コピーしたい任意の文字列を定義
        const textToCopy = escapeHtml('<?php echo $post; ?> <?php echo $address; ?>');

        // 一時的なテキストエリアを作成
        const tempInput = document.createElement('textarea');
        tempInput.value = textToCopy;
        document.body.appendChild(tempInput);

        // テキストエリアの内容を選択
        tempInput.select();
        tempInput.setSelectionRange(0, 99999); // モバイル対応

        // クリップボードにコピー
        document.execCommand('copy');

        // 一時的なテキストエリアを削除
        document.body.removeChild(tempInput);

        // コピー完了のメッセージ（必要に応じて）
        alert('住所がコピーされました: ' + textToCopy);
    });

    function copy_url_btn() {
        navigator.clipboard.writeText('https://ekitrip.com/station/?stacd=' + stacd)
        window.alert('コピーしました');
    }

    function open_company_web() {
        if (confirm('運営会社のWebサイトに移動しますか')) {
            window.open('<?php echo $company_url; ?>');
        }
    }

    //LINEshare
    document.getElementById('line-share-btn').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default link behavior
        var currentUrl = window.location.href; // Get the current URL
        var lineShareUrl = 'https://social-plugins.line.me/lineit/share?url=' + encodeURIComponent(currentUrl);
        window.open(lineShareUrl, '_blank'); // Open the LINE share URL in a new tab/window
    });

    //addfavorite
    function addFavoriteBtn() {
        const getArray = localStorage.getItem('favorite');
        const favoriteArray = JSON.parse(getArray);
        console.log(favoriteArray);

        const staMemoInput = document.getElementById('fav-sta-memo-input');
        const staMemo = escapeHtml(staMemoInput.value);

        const addStaInfo = {
            code: escapeHtml(<?php echo json_encode($stacd); ?>),
            name: escapeHtml(<?php echo json_encode($station_name); ?>),
            linecd: escapeHtml(<?php echo json_encode($line_cd); ?>),
            linename: escapeHtml(<?php echo json_encode($line_name); ?>),
            linecolor: escapeHtml(<?php echo json_encode($line_color); ?>),
            stamemo: staMemo,
        };

        if (getArray) {
            if (favoriteArray.length >= 100) {
                window.alert('保存できる駅は100件までです。');
            } else {
                let addPass = 'ok';
                for (let i = 0; i < favoriteArray.length; i++) {
                    if (favoriteArray[i].code == addStaInfo.code) {
                        window.alert('すでに登録されています');
                        addPass = 'no';
                        break;
                    }
                }
                if (addPass == 'ok') {
                    favoriteArray.push(addStaInfo);
                    localStorage.setItem('favorite', JSON.stringify(favoriteArray));
                    window.alert('登録しました');
                    location.reload();
                }
            }
        } else {
            localStorage.setItem('favorite', JSON.stringify([addStaInfo]));
            window.alert('登録しました');
            location.reload();
        }
    }

    //addVisit
    function addVisitBtn() {
        const getArray = localStorage.getItem('visit_history');
        const visitData = getArray ? JSON.parse(getArray) : [];
        const visitMemoInput = document.getElementById('visit-sta-memo-inout');
        const visitMemo = escapeHtml(visitMemoInput.value);

        var Year = escapeHtml(document.getElementById('year').value);
        var Month = escapeHtml(document.getElementById('month').value);
        var Day = escapeHtml(document.getElementById('day').value);
        var Hour = escapeHtml(document.getElementById('hour').value);
        var Minute = escapeHtml(document.getElementById('minute').value);

        const addVisitInfo = {
            type: `station`,
            date: {
                year: Year,
                month: Month,
                day: Day,
                hour: Hour,
                minute: Minute,
            },
            code: escapeHtml(<?php echo json_encode($stacd); ?>),
            g_code: escapeHtml(<?php echo json_encode($station_g_cd); ?>),
            name: escapeHtml(<?php echo json_encode($station_name); ?>),
            linecd: escapeHtml(<?php echo json_encode($line_cd); ?>),
            linename: escapeHtml(<?php echo json_encode($line_name); ?>),
            linecolor: escapeHtml(<?php echo json_encode($line_color); ?>),
            stamemo: visitMemo,
        }

        visitData.unshift(addVisitInfo);

        visitData.sort((a, b) => {
            const dateA = new Date(a.date.year, a.date.month - 1, a.date.day, a.date.hour, a.date.minute);
            const dateB = new Date(b.date.year, b.date.month - 1, b.date.day, b.date.hour, b.date.minute);
            return dateB - dateA;
        });

        localStorage.setItem('visit_history', JSON.stringify(visitData));
        window.alert('登録しました。');

        location.reload();
    }

    function page_print() {
        window.print();
    }


    function beta_err() {
        window.alert('この機能はBETA版では利用できません');
    }
</script>


</html>