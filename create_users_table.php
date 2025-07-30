<?php
// usersテーブル作成スクリプト
require_once('funcs.php');

try {
    $pdo = db_conn();
    
    // usersテーブルを作成
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL DEFAULT '',
        age INT,
        naiyou TEXT,
        indate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "usersテーブルが作成されました。<br>";
    
    // 既存のユーザーを確認
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "現在のユーザー数: " . $result['count'] . "<br>";
    
    // 管理者アカウントが存在しない場合は作成
    $admin_email = 'admin@system';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$admin_email]);
    $existing_admin = $stmt->fetch();
    
    if (!$existing_admin) {
        $admin_name = 'admin';
        $admin_password = 'admin123';
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users(name, email, password, age, naiyou) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$admin_name, $admin_email, $hashed_password, 30, 'システム管理者アカウント']);
        
        echo "管理者アカウントが作成されました。<br>";
        echo "メール: " . $admin_email . "<br>";
        echo "パスワード: " . $admin_password . "<br>";
    } else {
        echo "管理者アカウントは既に存在します。<br>";
    }
    
    echo "<br><a href='user_userlist.php'>ユーザー管理ページに移動</a>";
    
} catch(PDOException $e) {
    echo "エラーが発生しました: " . $e->getMessage();
}
?> 