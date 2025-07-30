<?php
// 1. セッションを開始（既存のセッションがあれば復元）
// ※ログアウト処理でもセッション情報にアクセスするため必須
session_start();

// 2. セッション変数をすべて削除（配列を空にする）
// $_SESSION['user_id']や$_SESSION['chk_ssid']などがすべてクリアされる
$_SESSION = [];

// 3. ブラウザ側のセッションクッキーを削除する処理
// session_name()でセッションIDの名前（通常は'PHPSESSID'）を取得
if (isset($_COOKIE[session_name()])) { 
    // setcookie()でクッキーの有効期限を過去（現在時刻-42000秒前）に設定
    // これによりブラウザ側のセッションクッキーが削除される
    // 第4引数の'/'はパスを指定（サイト全体で有効）
    setcookie(session_name(), '', time() - 42000, '/');
}

// 4. サーバー側のセッションファイル自体を完全に破棄
// /tmp/sess_xxxxxxxx といったセッションファイルが削除される
session_destroy();

// 5. ログアウト完了後、ダッシュボードにリダイレクト
// ユーザーを強制的にダッシュボードに移動させる
header("Location: dashboard.php");

// 6. PHP処理を終了（リダイレクト後の処理実行を防ぐ）
// header()の後は必ずexit()を書く習慣をつける
exit();
?>