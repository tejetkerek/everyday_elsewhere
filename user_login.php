<?php
session_start();

// デバッグ用：エラー表示を有効化
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 簡単なログイン処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // デバッグ用：入力値をログに記録
    error_log("Login attempt - Username: " . $username);
    error_log("Login attempt - Password length: " . strlen($password));
    
    // 簡単な認証（実際の運用では適切な認証が必要）
    if ($username === 'admin' && $password === 'password') {
        // セッションIDを再生成
        session_regenerate_id(true);
        
        // セッション情報を設定
        $_SESSION['chk_ssid'] = session_id();
        $_SESSION['kanri_flg'] = 1; // 管理者フラグ
        $_SESSION['user_email'] = 'admin@system';
        $_SESSION['user_name'] = 'admin';
        
        error_log("Login successful for admin");
        error_log("Session ID after login: " . session_id());
        error_log("Session chk_ssid: " . $_SESSION['chk_ssid']);
        
        header('Location: dashboard.php');
        exit();
    } else {
        error_log("Login failed - Invalid credentials");
        $error = 'ユーザー名またはパスワードが間違っています';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - 世界の小さな日常</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-purple-50 min-h-screen">
    <!-- ヘッダー -->
    <header class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="text-3xl">🌍</div>
                    <h1 class="text-2xl font-bold text-gray-800">世界の小さな日常</h1>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="dashboard.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>
                        ダッシュボード
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="min-h-screen flex items-center justify-center py-12">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">ログイン</h2>
                <p class="text-gray-600">世界の小さな日常</p>
            </div>

            <!-- デバッグ情報 -->
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                <strong>デバッグ情報:</strong><br>
                ユーザー名: <code>admin</code><br>
                パスワード: <code>password</code>
            </div>

            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6" id="loginForm">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2"></i>ユーザー名
                    </label>
                    <input type="text" id="username" name="username" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="admin"
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>パスワード
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="password">
                        <button type="button" id="togglePassword" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                                onclick="togglePasswordVisibility()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>ログイン
                </button>
            </form>

            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    アカウントをお持ちでない場合は
                    <a href="user_create_form.php" class="text-blue-600 hover:text-blue-800 font-medium">
                        新規登録
                    </a>
                    してください
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        // フォーム送信時のデバッグ
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            console.log('Login attempt:', { username, password });
        });
    </script>
</body>
</html>
