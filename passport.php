<?php
session_start();

// デバッグ用：セッション情報を確認
error_log("Session check - chk_ssid: " . ($_SESSION['chk_ssid'] ?? 'not set'));
error_log("Session check - session_id: " . session_id());

// ログインチェック
if (!isset($_SESSION['chk_ssid']) || $_SESSION['chk_ssid'] !== session_id()) {
    error_log("Login check failed - redirecting to login page");
    header('Location: user_login.php');
    exit();
}

error_log("Login check passed - proceeding to passport page");

// API関数を読み込み
require_once('country_api.php');

// デバッグ用：エラー表示を有効化
error_reporting(E_ALL);
ini_set('display_errors', 1);

// すべての国を取得（キャッシュ機能を使用）
$allCountries = getAllCountriesCached(86400); // 24時間キャッシュ

// デバッグ用：取得した国の数を確認
echo "<!-- Debug: 取得した国の数: " . count($allCountries) . " -->";

// 地域別に国を分類
$countriesByRegion = [];
foreach ($allCountries as $country) {
    $region = $country['region'];
    if (!isset($countriesByRegion[$region])) {
        $countriesByRegion[$region] = [];
    }
    $countriesByRegion[$region][] = $country;
}

// デバッグ用：地域別の国の数を確認
echo "<!-- Debug: 地域別の国数 -->";
foreach ($countriesByRegion as $region => $countries) {
    echo "<!-- $region: " . count($countries) . "か国 -->";
}

// 地域の日本語名マッピング
$regionNames = [
    'Asia' => 'アジア',
    'Europe' => 'ヨーロッパ',
    'Africa' => 'アフリカ',
    'Americas' => 'アメリカ',
    'Oceania' => 'オセアニア'
];

// 地域のアイコンマッピング
$regionIcons = [
    'Asia' => '🌏',
    'Europe' => '🇪🇺',
    'Africa' => '🌍',
    'Americas' => '🌎',
    'Oceania' => '🌏'
];

// 統計情報
$total_countries = count($allCountries);
$total_population = 0;
foreach ($allCountries as $country) {
    $total_population += (int)str_replace(',', '', $country['population']);
}

// 地域別統計
$regionStats = [];
foreach ($countriesByRegion as $region => $countries) {
    $regionStats[$region] = [
        'count' => count($countries),
        'population' => array_sum(array_map(function($c) { 
            return (int)str_replace(',', '', $c['population']); 
        }, $countries))
    ];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスポート - 世界の小さな日常</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .country-card {
            transition: all 0.3s ease;
        }
        .country-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .stamp-area {
            transition: all 0.3s ease;
        }
        .stamp-area:hover {
            transform: scale(1.1);
        }
        .stamped {
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            color: #8b4513;
            text-shadow: 0 0 10px #ffd700;
        }
        .visited-country {
            opacity: 0.8;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #0ea5e9;
        }
        .unvisited-country {
            opacity: 1;
            background: linear-gradient(135deg, #fefefe 0%, #f8fafc 100%);
            border: 2px solid #e2e8f0;
        }
        .region-section {
            margin-bottom: 2rem;
        }
        .country-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-400 to-purple-600 min-h-screen">
    <!-- ヘッダー -->
    <header class="bg-white/90 backdrop-blur-sm shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="text-3xl">📖</div>
                    <h1 class="text-xl font-bold text-gray-800">パスポート</h1>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="dashboard.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-globe mr-2"></i>地球儀に戻る
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- メインコンテンツ -->
    <div class="container mx-auto px-4 py-8">
        <!-- パスポート説明 -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white mb-4">世界のすべての国を探索しよう！</h2>
            <p class="text-white/90 text-lg">APIから取得した250以上の国と地域の情報を表示しています</p>
        </div>

        <!-- デバッグ情報表示 -->
        <?php if (empty($allCountries)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>デバッグ情報:</strong> 国データが取得できませんでした。API接続を確認してください。
        </div>
        <?php else: ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <strong>デバッグ情報:</strong> <?php echo count($allCountries); ?>か国のデータを取得しました。
        </div>
        <?php endif; ?>

        <!-- 統計情報 -->
        <div class="mb-8 bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-6">
            <div class="text-center">
                <h3 class="text-xl font-bold text-gray-800 mb-4">世界統計</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600"><?php echo $total_countries; ?></div>
                        <div class="text-sm text-gray-600">総国数</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600"><?php echo number_format($total_population); ?></div>
                        <div class="text-sm text-gray-600">総人口</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600" id="visitedCount">0</div>
                        <div class="text-sm text-gray-600">訪問済み</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-600" id="unvisitedCount"><?php echo $total_countries; ?></div>
                        <div class="text-sm text-gray-600">未訪問</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-red-600"><?php echo count($regionStats); ?></div>
                        <div class="text-sm text-gray-600">地域数</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 地域別カード -->
        <?php if (!empty($countriesByRegion)): ?>
        <?php foreach ($countriesByRegion as $region => $countries): ?>
        <div class="region-section bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-6">
            <div class="text-center mb-6">
                <div class="text-4xl mb-2"><?php echo $regionIcons[$region] ?? '🌍'; ?></div>
                <h3 class="text-2xl font-bold text-gray-800"><?php echo $regionNames[$region] ?? $region; ?></h3>
                <p class="text-sm text-gray-600">
                    <?php echo $regionStats[$region]['count']; ?>か国 / 
                    人口: <?php echo number_format($regionStats[$region]['population']); ?>人
                </p>
            </div>
            
            <div class="country-grid">
                <?php foreach ($countries as $country): ?>
                <div class="country-card unvisited-country rounded-lg p-4 border" 
                     id="country-<?php echo strtolower($country['name']); ?>">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="text-2xl">
                                <?php if ($country['flag_image']): ?>
                                    <img src="<?php echo $country['flag_image']; ?>" alt="<?php echo $country['name']; ?> flag" class="w-8 h-6 object-cover rounded shadow-sm">
                                <?php else: ?>
                                    <?php echo $country['flag']; ?>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800"><?php echo $country['name_jp'] ?? $country['name']; ?></h4>
                                <p class="text-xs text-gray-600"><?php echo $country['name']; ?></p>
                                <p class="text-xs text-blue-600">首都: <?php echo $country['capital']; ?></p>
                                <p class="text-xs text-green-600">人口: <?php echo $country['population']; ?></p>
                                <?php if (!empty($country['languages'])): ?>
                                    <p class="text-xs text-purple-600">言語: <?php echo implode(', ', $country['languages']); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($country['currencies'])): ?>
                                    <p class="text-xs text-orange-600">通貨: <?php echo implode(', ', $country['currencies']); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($country['timezones'])): ?>
                                    <p class="text-xs text-indigo-600">時差: <?php echo implode(', ', $country['timezones']); ?></p>
                                <?php endif; ?>
                                <?php if ($country['area'] > 0): ?>
                                    <p class="text-xs text-teal-600">面積: <?php echo number_format($country['area']); ?> km²</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex flex-col items-center space-y-2">
                            <div class="stamp-area w-12 h-12 border-2 border-gray-300 rounded-full flex items-center justify-center cursor-pointer" 
                                 id="stamp-<?php echo strtolower($country['name']); ?>">
                                <div class="text-center">
                                    <div class="text-xs text-gray-500" id="text-<?php echo strtolower($country['name']); ?>">訪問前</div>
                                </div>
                            </div>
                            <button class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded-full transition-colors" 
                                    onclick="visitCountry('<?php echo strtolower($country['name']); ?>', '<?php echo $country['name_jp'] ?? $country['name']; ?>')" 
                                    id="button-<?php echo strtolower($country['name']); ?>">
                                訪問
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            <strong>注意:</strong> 地域別の国データがありません。
        </div>
        <?php endif; ?>

        <!-- API情報表示 -->
        <div class="mt-8 bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-6">
            <div class="text-center">
                <h3 class="text-xl font-bold text-gray-800 mb-4">API情報</h3>
                <p class="text-sm text-gray-600 mb-2">このページは Countries Now API を使用してリアルタイムで国情報を取得しています</p>
                <div class="flex justify-center space-x-4 text-xs text-gray-500">
                    <span><i class="fas fa-database mr-1"></i>データソース: Countries Now API</span>
                    <span><i class="fas fa-clock mr-1"></i>更新頻度: リアルタイム</span>
                    <span><i class="fas fa-globe mr-1"></i>対応国数: <?php echo $total_countries; ?>+</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        let visitedCountries = [];

        function visitCountry(country, countryName) {
            const countryElement = document.getElementById(`country-${country}`);
            const stampElement = document.getElementById(`stamp-${country}`);
            const textElement = document.getElementById(`text-${country}`);
            const buttonElement = document.getElementById(`button-${country}`);
            
            if (!visitedCountries.includes(country)) {
                // 訪問
                visitedCountries.push(country);
                countryElement.classList.remove('unvisited-country');
                countryElement.classList.add('visited-country');
                
                // テキストを飛行機イラストに変更
                if (textElement) {
                    textElement.innerHTML = '<div class="text-lg">✈️</div>';
                    textElement.classList.remove('text-gray-500');
                    textElement.classList.add('text-blue-500');
                }
                
                // ボタンを非表示にする
                if (buttonElement) {
                    buttonElement.style.display = 'none';
                }
                
                // 訪問カウントを更新
                document.getElementById('visitedCount').textContent = visitedCountries.length;
                document.getElementById('unvisitedCount').textContent = <?php echo $total_countries; ?> - visitedCountries.length;
                
                // 成功メッセージ
                alert(`${countryName}を訪問しました！`);
            } else {
                // 既に訪問済み
                alert('この国は既に訪問済みです！');
            }
        }

        // ページ読み込み時にローカルストレージから情報を復元
        window.addEventListener('load', function() {
            const savedVisited = localStorage.getItem('visited_countries');
            
            if (savedVisited) {
                visitedCountries = JSON.parse(savedVisited);
                visitedCountries.forEach(country => {
                    const countryElement = document.getElementById(`country-${country}`);
                    const textElement = document.getElementById(`text-${country}`);
                    const buttonElement = document.getElementById(`button-${country}`);
                    
                    if (countryElement) {
                        countryElement.classList.remove('unvisited-country');
                        countryElement.classList.add('visited-country');
                        
                        // テキストを飛行機イラストに変更
                        if (textElement) {
                            textElement.innerHTML = '<div class="text-lg">✈️</div>';
                            textElement.classList.remove('text-gray-500');
                            textElement.classList.add('text-blue-500');
                        }
                        
                        // ボタンを非表示にする
                        if (buttonElement) {
                            buttonElement.style.display = 'none';
                        }
                    }
                });
                document.getElementById('visitedCount').textContent = visitedCountries.length;
                document.getElementById('unvisitedCount').textContent = <?php echo $total_countries; ?> - visitedCountries.length;
            }
        });

        // 情報をローカルストレージに保存
        function saveData() {
            localStorage.setItem('visited_countries', JSON.stringify(visitedCountries));
        }

        // ページ離脱時に保存
        window.addEventListener('beforeunload', saveData);
    </script>
</body>
</html> 