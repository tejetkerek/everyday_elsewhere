<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>セッション情報デバッグ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">セッション情報デバッグ</h1>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">現在のセッション情報</h2>
            <div class="space-y-2">
                <p><strong>Session ID:</strong> <?php echo session_id(); ?></p>
                <p><strong>chk_ssid:</strong> <?php echo $_SESSION['chk_ssid'] ?? '未設定'; ?></p>
                <p><strong>user_email:</strong> <?php echo $_SESSION['user_email'] ?? '未設定'; ?></p>
                <p><strong>user_name:</strong> <?php echo $_SESSION['user_name'] ?? '未設定'; ?></p>
                <p><strong>user_id:</strong> <?php echo $_SESSION['user_id'] ?? '未設定'; ?></p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">管理者権限チェック</h2>
            <div class="space-y-2">
                <?php
                $isLoggedIn = isset($_SESSION['chk_ssid']) && $_SESSION['chk_ssid'] === session_id();
                $isAdmin = isset($_SESSION['user_email']) && $_SESSION['user_email'] === 'admin@system';
                ?>
                <p><strong>ログイン状態:</strong> <?php echo $isLoggedIn ? '✅ ログイン中' : '❌ 未ログイン'; ?></p>
                <p><strong>管理者権限:</strong> <?php echo $isAdmin ? '✅ 管理者' : '❌ 一般ユーザー'; ?></p>
                <p><strong>ユーザー管理アクセス可能:</strong> <?php echo ($isLoggedIn && $isAdmin) ? '✅ 可能' : '❌ 不可能'; ?></p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">アクション</h2>
            <div class="space-y-2">
                <a href="dashboard.php" class="inline-block bg-blue-500 text-white px-4 py-2 rounded">ダッシュボードに戻る</a>
                <a href="user_userlist.php" class="inline-block bg-green-500 text-white px-4 py-2 rounded ml-2">ユーザー管理を試す</a>
            </div>
        </div>
    </div>
</body>
</html> 