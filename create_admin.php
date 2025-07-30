<?php
// 管理者アカウント作成スクリプト
require_once('funcs.php');

try {
    $pdo = db_conn();
    
    // 管理者メールアドレスをチェック
    $admin_email = 'admin@system';
    
    // 既存の管理者アカウントをチェック
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$admin_email]);
    $existing_admin = $stmt->fetch();
    
    if ($existing_admin) {
        echo "管理者アカウントは既に存在します。<br>";
        echo "メール: " . $existing_admin['email'] . "<br>";
        echo "名前: " . $existing_admin['name'] . "<br>";
        echo "ID: " . $existing_admin['id'] . "<br>";
    } else {
        // 管理者アカウントを作成
        $admin_name = 'システム管理者';
        $admin_password = 'admin123'; // 簡単なパスワード
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users(name, email, password, age, naiyou) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$admin_name, $admin_email, $hashed_password, 30, 'システム管理者アカウント']);
        
        echo "管理者アカウントが作成されました！<br>";
        echo "メール: " . $admin_email . "<br>";
        echo "パスワード: " . $admin_password . "<br>";
        echo "名前: " . $admin_name . "<br>";
    }
    
    echo "<br><a href='user_login.php'>ログインページに戻る</a>";
    
} catch(PDOException $e) {
    echo "エラーが発生しました: " . $e->getMessage();
}
?> 