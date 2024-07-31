function browser_judge(){
    // ユーザーエージェントを取得
    const userAgent = navigator.userAgent || navigator.vendor || window.opera;

    // 独自のブラウザかどうかを判定する関数
    function isCustomBrowser() {
        // 独自のブラウザに特有のユーザーエージェントを含むかチェック
        return /Instagram|Line|FB_IAB/.test(userAgent);
    }
    // ユーザーが独自のブラウザでサイトを開いている場合
    if (isCustomBrowser()) {
        // メッセージを表示
        const browser_alert = document.getElementById('browser_alert');
        browser_alert.style.display = 'block';
    }
}

    function BA_copyButton(copyContent) {

        // 上記要素をクリップボードにコピーする
        navigator.clipboard.writeText(copyContent)
        window.alert('リンクをコピーしました。デフォルトのブラウザ（SafariやChrome）に貼り付けてアクセスしてください');
    }
