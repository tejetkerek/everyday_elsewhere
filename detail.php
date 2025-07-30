<?php
// 0. SESSION開始！！
session_start();
//１．関数群の読み込み
require_once('funcs.php');
// loginCheck(); // 一時的に無効化


$id = $_GET['id']; //?id~**を受け取る
require_once('funcs.php');
$pdo = db_conn();

//２．データ登録SQL作成
$stmt = $pdo->prepare('SELECT * FROM users WHERE id=:id;');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
if ($status == false) {
    sql_error($stmt);
} else {
    $row = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー編集 - 世界の小さな日常パスポート</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="gradient-bg">
    <!-- ヘッダー -->
    <header class="bg-white/10 backdrop-blur-sm shadow-sm sticky top-0 z-10">
        <div class="max-w-6xl mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="text-3xl">✏️</div>
                    <h1 class="text-2xl font-bold text-white">ユーザー編集</h1>
                </div>
                <div class="flex space-x-3">
                    <a href="dashboard.php" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-home mr-2"></i>ダッシュボード
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="form-card rounded-2xl p-8 shadow-xl">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">ユーザー情報を編集</h2>
                <p class="text-gray-600">ユーザーの情報を更新します</p>
            </div>

            <form method="POST" action="user_update.php" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2"></i>名前 *
                        </label>
                        <input type="text" id="name" name="name" required
                               value="<?= htmlspecialchars($row['name']) ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2"></i>メールアドレス *
                        </label>
                        <input type="email" id="email" name="email" required
                               value="<?= htmlspecialchars($row['email']) ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="age" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-birthday-cake mr-2"></i>年齢
                        </label>
                        <input type="number" id="age" name="age" min="1" max="120"
                               value="<?= htmlspecialchars($row['age']) ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="naiyou" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-globe mr-2"></i>興味のある国
                        </label>
                        <input type="text" id="naiyou" name="naiyou"
                               value="<?= htmlspecialchars($row['naiyou']) ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <input type="hidden" name="id" value="<?= $id ?>">

                <div class="flex justify-center space-x-4 pt-6">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-save mr-2"></i>更新する
                    </button>
                    <a href="user_userlist.php" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>キャンセル
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- フッター -->
    <footer class="bg-white/10 backdrop-blur-sm border-t border-white/20 mt-12">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="text-center text-white/70">
                <p>&copy; 2025 世界の小さな日常パスポート</p>
                <p class="text-sm mt-2">子供たちの世界への好奇心を育てる</p>
            </div>
        </div>
    </footer>
</body>
</html>
