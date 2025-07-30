<?php
header('Content-Type: application/json');

//2. DB接続します
require_once('funcs.php');
$pdo = db_conn();

// POSTデータを取得
$name = $_POST['name'] ?? '';
$birthday = $_POST['birthday'] ?? '';
$dream_destination = $_POST['dream_destination'] ?? '';

// バリデーション
if (empty($name) || empty($birthday) || empty($dream_destination)) {
    echo json_encode(['success' => false, 'message' => 'すべての項目を入力してください']);
    exit;
}

try {
    // 既存のユーザーを更新するか、新規作成
    $stmt = $pdo->prepare("INSERT INTO users (name, birthday, dream_destination) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE name = VALUES(name), birthday = VALUES(birthday), dream_destination = VALUES(dream_destination)");
    $stmt->execute([$name, $birthday, $dream_destination]);
    
    echo json_encode(['success' => true, 'message' => 'プロフィールが更新されました']);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'データベースエラー: ' . $e->getMessage()]);
}
?> 