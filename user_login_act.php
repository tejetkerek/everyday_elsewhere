<?php
//最初にSESSIONを開始！！ココ大事！！
session_start();

//POST値を受け取る
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

//2. DB接続します
require_once('funcs.php');
$pdo = db_conn();


//2. データ登録SQL作成
// adminの場合は特別処理、それ以外はemailで検索
if ($username === 'admin') {
    // adminの場合は特別な認証処理
    if ($password === 'password') {
        session_regenerate_id(true);
        $_SESSION['chk_ssid'] = session_id();
        $_SESSION['admin'] = true;
        $_SESSION['kanri_flg'] = 1; // 管理者フラグを設定
        $_SESSION['user_id'] = 0;
        $_SESSION['user_name'] = 'admin';
        $_SESSION['user_email'] = 'admin@system';
        echo 'success';
        exit();
    } else {
        echo 'failed';
        exit();
    }
} else {
    // 通常のユーザーはemailで検索
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->bindValue(1, $username, PDO::PARAM_STR);
    $status = $stmt->execute();
}

//3. SQL実行時にエラーがある場合STOP
if($status === false){
    echo 'SQL実行エラー';
    exit();
}

//4. 抽出データ数を取得
$val = $stmt->fetch();

if($val && password_verify(mb_convert_encoding($password, 'UTF-8', 'auto'), $val['password'])){
    session_regenerate_id(true);
    $_SESSION['chk_ssid'] = session_id();
    $_SESSION['kanri_flg'] = 0; // 一般ユーザーフラグを設定
    $_SESSION['user_id'] = $val['id'];
    $_SESSION['user_name'] = $val['name'];
    $_SESSION['user_email'] = $val['email'];

    //Login成功時 該当レコードがあればSESSIONに値を代入
    echo 'success';
}else{
    //Login失敗時
    if (!$val) {
        echo 'user_not_found';
    } else {
        echo 'password_mismatch';
    }
}
exit();