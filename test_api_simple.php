<?php
// シンプルなAPI接続テスト
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>API接続テスト</h1>";

// cURLが利用可能かチェック
if (!function_exists('curl_init')) {
    echo "<p style='color: red;'>❌ cURLが利用できません</p>";
    exit;
}
echo "<p style='color: green;'>✅ cURLが利用可能です</p>";

// APIリクエスト
$apiUrl = "https://countriesnow.space/api/v0.1/countries";
echo "<p>API URL: $apiUrl</p>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

echo "<p>APIリクエスト中...</p>";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
$info = curl_getinfo($ch);
curl_close($ch);

echo "<h2>結果</h2>";
echo "<p>HTTP Code: $httpCode</p>";
echo "<p>Response Size: " . strlen($response) . " bytes</p>";
echo "<p>Total Time: " . $info['total_time'] . " seconds</p>";

if ($error) {
    echo "<p style='color: red;'>❌ CURL Error: $error</p>";
} else {
    echo "<p style='color: green;'>✅ CURL Error: なし</p>";
}

if ($httpCode === 200 && $response) {
    $data = json_decode($response, true);
    if ($data && isset($data['data'])) {
        echo "<p style='color: green;'>✅ JSONデコード成功</p>";
        echo "<p>取得した国数: " . count($data['data']) . "</p>";
        
        // 最初の5か国を表示
        echo "<h3>最初の5か国:</h3>";
        for ($i = 0; $i < min(5, count($data['data'])); $i++) {
            $country = $data['data'][$i];
            echo "<p>" . ($i + 1) . ". " . $country['country'] . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ JSONデコード失敗</p>";
    }
} else {
    echo "<p style='color: red;'>❌ APIリクエスト失敗</p>";
    if ($response) {
        echo "<p>Response preview: " . substr($response, 0, 200) . "...</p>";
    }
}

// ネットワーク接続テスト
echo "<h2>ネットワーク接続テスト</h2>";
$testUrls = [
    'https://www.google.com' => 'Google',
    'https://countriesnow.space' => 'Countries Now API',
    'https://httpbin.org/get' => 'HTTPBin'
];

foreach ($testUrls as $url => $name) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        echo "<p style='color: green;'>✅ $name: 接続成功</p>";
    } else {
        echo "<p style='color: red;'>❌ $name: 接続失敗 (HTTP $httpCode)</p>";
    }
}
?> 