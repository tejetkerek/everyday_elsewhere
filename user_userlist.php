<?php
// セッション開始
session_start();

// デバッグ情報を表示
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!-- Debug: セッション情報 -->";
echo "<!-- chk_ssid: " . ($_SESSION['chk_ssid'] ?? '未設定') . " -->";
echo "<!-- session_id: " . session_id() . " -->";
echo "<!-- user_email: " . ($_SESSION['user_email'] ?? '未設定') . " -->";

// 管理者権限チェック
if (!isset($_SESSION['chk_ssid']) || $_SESSION['chk_ssid'] !== session_id() || !isset($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@system') {
    echo "<!-- Debug: 権限チェック失敗 -->";
    header('Location: dashboard.php');
    exit();
}

echo "<!-- Debug: 権限チェック成功 -->";

//2. DB接続します
require_once('funcs.php');
$pdo = db_conn();

echo "<!-- Debug: DB接続成功 -->";

try {
    // ユーザー一覧を取得
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<!-- Debug: ユーザー取得成功 - " . count($users) . "件 -->";
    
} catch(PDOException $e) {
    $error_message = 'データベースエラー: ' . $e->getMessage();
    $users = [];
    echo "<!-- Debug: データベースエラー - " . $e->getMessage() . " -->";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー管理 - 世界の小さな日常</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-purple-50 min-h-screen">
    <!-- ヘッダー -->
    <header class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="text-3xl">👥</div>
                    <h1 class="text-2xl font-bold text-gray-800">ユーザー管理</h1>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2 bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                        <div class="w-2 h-2 bg-purple-500 rounded-full animate-pulse"></div>
                        <span class="font-medium">管理者としてログイン中</span>
                    </div>
                    <a href="dashboard.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>ダッシュボード
                    </a>
                    <a href="user_logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                        <i class="fas fa-sign-out-alt mr-1"></i>ログアウト
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- ページタイトル -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">ユーザー一覧</h2>
            <p class="text-gray-600">登録されているユーザーの管理を行います</p>
        </div>

        <!-- エラーメッセージ -->
        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- 新規ユーザー追加ボタン -->
        <div class="flex justify-between items-center mb-6">
            <div class="text-sm text-gray-600">
                <i class="fas fa-users mr-1"></i>
                登録ユーザー数: <span class="font-bold"><?php echo count($users); ?></span>
            </div>
            <a href="user_create_form.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors font-medium">
                <i class="fas fa-user-plus mr-2"></i>新規ユーザー追加
            </a>
        </div>

        <!-- ユーザー一覧テーブル -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">名前</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">メールアドレス</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">年齢</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">興味のある国</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">登録日</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <div class="text-4xl mb-2">👥</div>
                                    <div class="text-lg font-medium mb-2">ユーザーが登録されていません</div>
                                    <div class="text-sm text-gray-400">新規ユーザーを追加してください</div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        #<?php echo htmlspecialchars($user['id']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($user['name']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($user['email']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($user['age'] ?: '-'); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                        <?php echo htmlspecialchars($user['naiyou'] ?: '-'); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('Y/m/d H:i', strtotime($user['indate'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="detail.php?id=<?php echo $user['id']; ?>" 
                                               class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg transition-colors">
                                                <i class="fas fa-edit mr-1"></i>編集
                                            </a>
                                            <a href="delete.php?id=<?php echo $user['id']; ?>" 
                                               class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-lg transition-colors"
                                               onclick="return confirm('ユーザー「<?php echo htmlspecialchars($user['name']); ?>」を削除しますか？')">
                                                <i class="fas fa-trash mr-1"></i>削除
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 統計情報 -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
            <div class="bg-white rounded-xl p-6 text-center shadow">
                <div class="text-3xl font-bold text-blue-600"><?php echo count($users); ?></div>
                <div class="text-sm text-gray-600">総ユーザー数</div>
            </div>
            <div class="bg-white rounded-xl p-6 text-center shadow">
                <div class="text-3xl font-bold text-green-600">
                    <?php echo count(array_filter($users, function($user) { return $user['age'] && $user['age'] < 18; })); ?>
                </div>
                <div class="text-sm text-gray-600">未成年ユーザー</div>
            </div>
            <div class="bg-white rounded-xl p-6 text-center shadow">
                <div class="text-3xl font-bold text-purple-600">
                    <?php echo count(array_filter($users, function($user) { return $user['age'] && $user['age'] >= 18; })); ?>
                </div>
                <div class="text-sm text-gray-600">成年ユーザー</div>
            </div>
        </div>
    </div>

    <!-- フッター -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="text-center text-gray-600">
                <p>&copy; 2025 世界の小さな日常</p>
                <p class="text-sm mt-2">管理者専用ページ</p>
            </div>
        </div>
    </footer>
</body>
</html> 