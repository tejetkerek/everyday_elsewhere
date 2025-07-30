<?php
// 0. SESSION開始！！
session_start();
//１．関数群の読み込み
require_once('funcs.php');
// loginCheck(); // 一時的に無効化

// 削除確認
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    $id = $_GET['id'];
    echo '<!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>削除確認 - 世界の小さな日常パスポート</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    </head>
    <body class="bg-gradient-to-br from-blue-50 to-purple-50 min-h-screen">
        <div class="min-h-screen flex items-center justify-center">
            <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <div class="text-6xl mb-4">⚠️</div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">削除確認</h2>
                    <p class="text-gray-600">このユーザーを削除しますか？</p>
                </div>
                
                <div class="flex space-x-4">
                    <a href="delete.php?id=' . $id . '&confirm=yes" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors text-center">
                        <i class="fas fa-trash mr-2"></i>削除する
                    </a>
                    <a href="user_userlist.php" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition-colors text-center">
                        <i class="fas fa-times mr-2"></i>キャンセル
                    </a>
                </div>
            </div>
        </div>
    </body>
    </html>';
    exit();
}

//1. POSTデータ取得
$id = $_GET['id'];

//2. DB接続します
require_once('funcs.php');
$pdo = db_conn();

//３．データ登録SQL作成
$stmt = $pdo->prepare('DELETE FROM users WHERE id = :id;');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //実行

//４．データ登録処理後
if ($status === false) {
    sql_error($stmt);
} else {
    redirect('user_userlist.php');
}
