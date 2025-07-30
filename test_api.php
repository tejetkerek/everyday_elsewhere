<?php
// API接続テスト用ファイル
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>API接続テスト</h1>";

// cURLが利用可能かチェック
if (!function_exists('curl_init')) {
    echo "<p style='color: red;'>❌ cURL拡張機能が利用できません</p>";
    exit;
} else {
    echo "<p style='color: green;'>✅ cURL拡張機能が利用可能です</p>";
}

// 簡単なAPIテスト
$apiUrl = "https://restcountries.com/v3.1/all";

echo "<h2>API接続テスト</h2>";
echo "<p>テストURL: $apiUrl</p>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
$info = curl_getinfo($ch);
curl_close($ch);

echo "<h3>接続結果</h3>";
echo "<ul>";
echo "<li>HTTP Code: $httpCode</li>";
echo "<li>Response Length: " . strlen($response) . " bytes</li>";
echo "<li>Total Time: " . $info['total_time'] . " seconds</li>";

if ($error) {
    echo "<li style='color: red;'>CURL Error: $error</li>";
} else {
    echo "<li style='color: green;'>CURL Error: なし</li>";
}
echo "</ul>";

if ($httpCode === 200 && $response) {
    $data = json_decode($response, true);
    if ($data) {
        echo "<p style='color: green;'>✅ API接続成功！取得した国の数: " . count($data) . "</p>";
        
        // 最初の3つの国の情報を表示
        echo "<h3>サンプルデータ（最初の3か国）</h3>";
        for ($i = 0; $i < min(3, count($data)); $i++) {
            $country = $data[$i];
            echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
            echo "<strong>" . ($country['name']['common'] ?? 'Unknown') . "</strong><br>";
            echo "首都: " . ($country['capital'][0] ?? 'Unknown') . "<br>";
            echo "地域: " . ($country['region'] ?? 'Unknown') . "<br>";
            echo "人口: " . number_format($country['population'] ?? 0) . "<br>";
            echo "国旗: " . ($country['flags']['emoji'] ?? '🏳️') . "<br>";
            echo "</div>";
        }
    } else {
        echo "<p style='color: red;'>❌ JSONデコードに失敗しました</p>";
        echo "<p>Response preview: " . substr($response, 0, 200) . "...</p>";
    }
} else {
    echo "<p style='color: red;'>❌ API接続に失敗しました</p>";
    echo "<p>HTTP Code: $httpCode</p>";
    if ($response) {
        echo "<p>Response: " . substr($response, 0, 200) . "...</p>";
    }
}

// キャッシュディレクトリの確認
echo "<h2>キャッシュディレクトリ確認</h2>";
$cacheDir = "cache";
if (!is_dir($cacheDir)) {
    echo "<p>キャッシュディレクトリを作成中...</p>";
    if (mkdir($cacheDir, 0755, true)) {
        echo "<p style='color: green;'>✅ キャッシュディレクトリを作成しました</p>";
    } else {
        echo "<p style='color: red;'>❌ キャッシュディレクトリの作成に失敗しました</p>";
    }
} else {
    echo "<p style='color: green;'>✅ キャッシュディレクトリが存在します</p>";
}

// 書き込み権限の確認
if (is_writable($cacheDir)) {
    echo "<p style='color: green;'>✅ キャッシュディレクトリに書き込み権限があります</p>";
} else {
    echo "<p style='color: red;'>❌ キャッシュディレクトリに書き込み権限がありません</p>";
}

echo "<h2>推奨される解決策</h2>";
echo "<ol>";
echo "<li>インターネット接続を確認してください</li>";
echo "<li>ファイアウォールがAPI接続をブロックしていないか確認してください</li>";
echo "<li>プロキシ設定が必要な場合は、PHPのcURL設定を確認してください</li>";
echo "<li>サーバーのPHP設定で外部URLへのアクセスが許可されているか確認してください</li>";
echo "</ol>";
?> 