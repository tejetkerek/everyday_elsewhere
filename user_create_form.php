<?php
// 文字エンコーディング設定
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_regex_encoding('UTF-8');

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームデータの取得とバリデーション
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $age = (int)($_POST['age'] ?? 0);
    $naiyou = trim($_POST['naiyou'] ?? '');
    
    // バリデーション
    if (empty($name)) {
        $error = '名前を入力してください';
    } elseif (empty($email)) {
        $error = 'メールアドレスを入力してください';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = '正しいメールアドレスを入力してください';
    } elseif (empty($password)) {
        $error = 'パスワードを入力してください';
    } elseif (strlen($password) < 6) {
        $error = 'パスワードは6文字以上で入力してください';
    } elseif ($password !== $confirm_password) {
        $error = 'パスワードが一致しません';
    } elseif ($age < 1 || $age > 120) {
        $error = '正しい年齢を入力してください';
    } else {

 //2. DB接続します
require_once('funcs.php');
$pdo = db_conn();



            // ユーザーテーブルが存在しない場合は作成
            $pdo->exec("CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL DEFAULT '',
                age INT,
                naiyou TEXT,
                indate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            
            // 既存のテーブルにpasswordカラムが存在しない場合は追加
            try {
                $pdo->exec("ALTER TABLE users ADD COLUMN password VARCHAR(255) NOT NULL DEFAULT '' AFTER email");
            } catch(PDOException $e) {
                // カラムが既に存在する場合はエラーを無視
            }
            
            // パスワードをハッシュ化
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // ユーザーを挿入
            $stmt = $pdo->prepare("INSERT INTO users(name, email, password, age, naiyou) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_password, $age, $naiyou]);
            
            $message = 'ユーザーが正常に作成されました！';
            
            // フォームをクリア
            $_POST = [];
            
            // 成功フラグを設定
            $success = true;
            
        } catch(PDOException $e) {
            $error = 'データベースエラーが発生しました: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規ユーザー作成 - 世界の小さな日常</title>
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
                    <div class="text-3xl">👤</div>
                    <h1 class="text-2xl font-bold text-white">新規ユーザー作成</h1>
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
                <h2 class="text-3xl font-bold text-gray-800 mb-2">新しいユーザーを作成</h2>
                <p class="text-gray-600">世界の小さな日常の新しいメンバーを追加します</p>
            </div>

            <?php if ($message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6" id="userForm" autocomplete="off">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2"></i>名前 *
                        </label>
                        <input type="text" id="name" name="name" required autocomplete="off"
                               value="<?php echo isset($success) ? '' : htmlspecialchars($_POST['name'] ?? ''); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="例：田中太郎">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2"></i>メールアドレス *
                        </label>
                        <input type="email" id="email" name="email" required autocomplete="off"
                               value="<?php echo isset($success) ? '' : htmlspecialchars($_POST['email'] ?? ''); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="例：tanaka@example.com">
                    </div>

                    <div>
                        <label for="age" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-birthday-cake mr-2"></i>年齢
                        </label>
                        <input type="number" id="age" name="age" min="1" max="120"
                               value="<?php echo isset($success) ? '' : htmlspecialchars($_POST['age'] ?? ''); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="例：25">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2"></i>パスワード *
                        </label>
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="6文字以上で入力">
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2"></i>パスワード確認 *
                        </label>
                        <input type="password" id="confirm_password" name="confirm_password" required autocomplete="new-password"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="パスワードを再入力">
                    </div>

                    <div>
                        <label for="naiyou" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-globe mr-2"></i>興味のある国
                        </label>
                        <input type="text" id="naiyou" name="naiyou"
                               value="<?php echo isset($success) ? '' : htmlspecialchars($_POST['naiyou'] ?? ''); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="例：日本、イタリア、フランス">
                    </div>
                </div>

                <div class="flex justify-center space-x-4 pt-6">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-user-plus mr-2"></i>ユーザーを作成
                    </button>
                    <a href="dashboard.php" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>ダッシュボードに戻る
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- フッター -->
    <footer class="bg-white/10 backdrop-blur-sm border-t border-white/20 mt-12">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="text-center text-white/70">
                <p>&copy; 2025 世界の小さな日常</p>
                <p class="text-sm mt-2">子供たちの世界への好奇心を育てる</p>
            </div>
        </div>
    </footer>

    <script>
        // 成功メッセージが表示された場合、フォームをクリア
        <?php if (isset($success) && $success): ?>
        document.addEventListener('DOMContentLoaded', function() {
            // フォームをリセット
            document.getElementById('userForm').reset();
            
            // すべての入力フィールドをクリア
            const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="number"], input[type="password"]');
            inputs.forEach(input => {
                input.value = '';
            });
            
            // ブラウザの自動補完を無効化
            document.getElementById('userForm').setAttribute('autocomplete', 'off');
        });
        <?php endif; ?>
        
        // ページ読み込み時にフォームの自動補完を無効化
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('userForm').setAttribute('autocomplete', 'off');
        });
    </script>
</body>
</html> 