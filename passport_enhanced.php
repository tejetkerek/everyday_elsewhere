<?php
session_start();

// ログインチェック
if (!isset($_SESSION['chk_ssid']) || $_SESSION['chk_ssid'] !== session_id()) {
    header('Location: user_login.php');
    exit();
}

// API関数を読み込み
require_once('country_api.php');

// 国情報を取得する関数
function getCountryDataForPassport($countryName) {
    $countryInfo = getCountryInfoCached($countryName);
    
    if ($countryInfo) {
        return [
            'name' => $countryInfo['name_jp'] ?? $countryInfo['name'],
            'name_en' => $countryInfo['name'],
            'flag' => $countryInfo['flag'],
            'capital' => $countryInfo['capital'],
            'population' => $countryInfo['population'],
            'region' => $countryInfo['region'],
            'languages' => $countryInfo['languages'],
            'currencies' => $countryInfo['currencies'],
            'area' => $countryInfo['area'],
            'flag_url' => $countryInfo['flag_url']
        ];
    }
    
    return null;
}

// パスポートに表示する国のリスト（API版）
$apiCountries = [
    'Japan' => ['name' => '日本', 'emoji' => '🍜', 'breakfast' => 'お味噌汁とご飯'],
    'Vietnam' => ['name' => 'ベトナム', 'emoji' => '🍜', 'breakfast' => 'フォー'],
    'France' => ['name' => 'フランス', 'emoji' => '🥐', 'breakfast' => 'クロワッサン'],
    'Italy' => ['name' => 'イタリア', 'emoji' => '☕', 'breakfast' => 'カプチーノ'],
    'Brazil' => ['name' => 'ブラジル', 'emoji' => '☕', 'breakfast' => 'カフェジーニョ'],
    'Egypt' => ['name' => 'エジプト', 'emoji' => '🍞', 'breakfast' => 'フール'],
    'India' => ['name' => 'インド', 'emoji' => '🍛', 'breakfast' => 'ダール'],
    'South Korea' => ['name' => '韓国', 'emoji' => '🍚', 'breakfast' => 'キムチ']
];

// 各国の情報を取得
$countriesData = [];
foreach ($apiCountries as $enName => $countryInfo) {
    $apiData = getCountryDataForPassport($enName);
    if ($apiData) {
        $countriesData[$countryInfo['name']] = array_merge($apiData, $countryInfo);
    }
}

// 統計情報
$total_countries = count($countriesData);
$total_population = 0;
foreach ($countriesData as $country) {
    $total_population += (int)str_replace(',', '', $country['population']);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスポート（API統合版） - 世界の小さな日常</title>
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
    </style>
</head>
<body class="bg-gradient-to-br from-blue-400 to-purple-600 min-h-screen">
    <!-- ヘッダー -->
    <header class="bg-white/90 backdrop-blur-sm shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="text-3xl">📖</div>
                    <h1 class="text-xl font-bold text-gray-800">パスポート（API統合版）</h1>
                </div>
                <a href="dashboard.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-globe mr-2"></i>地球儀に戻る
                </a>
            </div>
        </div>
    </header>

    <!-- メインコンテンツ -->
    <div class="container mx-auto px-4 py-8">
        <!-- パスポート説明 -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white mb-4">リアルタイム国情報で朝ごはんを体験！</h2>
            <p class="text-white/90 text-lg">APIから取得した最新の国情報と朝ごはん文化を学ぼう！</p>
        </div>

        <!-- 地域別カード -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- アジア -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                <div class="text-center mb-4">
                    <div class="text-4xl mb-2">🌏</div>
                    <h3 class="text-xl font-bold text-gray-800">アジア</h3>
                </div>
                <div class="space-y-3">
                    <?php foreach ($countriesData as $jpName => $countryData): ?>
                    <?php if ($countryData['region'] === 'Asia'): ?>
                    <div class="country-card bg-gradient-to-r from-red-50 to-red-100 rounded-lg p-4 border border-red-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="text-2xl"><?php echo $countryData['flag']; ?></div>
                                <div>
                                    <h4 class="font-semibold text-gray-800"><?php echo $countryData['name']; ?></h4>
                                    <p class="text-xs text-gray-600"><?php echo $countryData['breakfast']; ?></p>
                                    <p class="text-xs text-blue-600">首都: <?php echo $countryData['capital']; ?></p>
                                </div>
                            </div>
                            <div class="stamp-area w-12 h-12 border-2 border-gray-300 rounded-full flex items-center justify-center cursor-pointer" 
                                 onclick="stampCountry('<?php echo strtolower($countryData['name_en']); ?>')" 
                                 id="stamp-<?php echo strtolower($countryData['name_en']); ?>">
                                <div class="text-lg text-gray-300" id="icon-<?php echo strtolower($countryData['name_en']); ?>"><?php echo $countryData['emoji']; ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ヨーロッパ -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                <div class="text-center mb-4">
                    <div class="text-4xl mb-2">🇪🇺</div>
                    <h3 class="text-xl font-bold text-gray-800">ヨーロッパ</h3>
                </div>
                <div class="space-y-3">
                    <?php foreach ($countriesData as $jpName => $countryData): ?>
                    <?php if ($countryData['region'] === 'Europe'): ?>
                    <div class="country-card bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="text-2xl"><?php echo $countryData['flag']; ?></div>
                                <div>
                                    <h4 class="font-semibold text-gray-800"><?php echo $countryData['name']; ?></h4>
                                    <p class="text-xs text-gray-600"><?php echo $countryData['breakfast']; ?></p>
                                    <p class="text-xs text-blue-600">首都: <?php echo $countryData['capital']; ?></p>
                                </div>
                            </div>
                            <div class="stamp-area w-12 h-12 border-2 border-gray-300 rounded-full flex items-center justify-center cursor-pointer" 
                                 onclick="stampCountry('<?php echo strtolower($countryData['name_en']); ?>')" 
                                 id="stamp-<?php echo strtolower($countryData['name_en']); ?>">
                                <div class="text-lg text-gray-300" id="icon-<?php echo strtolower($countryData['name_en']); ?>"><?php echo $countryData['emoji']; ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- その他の地域 -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                <div class="text-center mb-4">
                    <div class="text-4xl mb-2">🌍</div>
                    <h3 class="text-xl font-bold text-gray-800">その他の地域</h3>
                </div>
                <div class="space-y-3">
                    <?php foreach ($countriesData as $jpName => $countryData): ?>
                    <?php if ($countryData['region'] !== 'Asia' && $countryData['region'] !== 'Europe'): ?>
                    <div class="country-card bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="text-2xl"><?php echo $countryData['flag']; ?></div>
                                <div>
                                    <h4 class="font-semibold text-gray-800"><?php echo $countryData['name']; ?></h4>
                                    <p class="text-xs text-gray-600"><?php echo $countryData['breakfast']; ?></p>
                                    <p class="text-xs text-blue-600">首都: <?php echo $countryData['capital']; ?></p>
                                </div>
                            </div>
                            <div class="stamp-area w-12 h-12 border-2 border-gray-300 rounded-full flex items-center justify-center cursor-pointer" 
                                 onclick="stampCountry('<?php echo strtolower($countryData['name_en']); ?>')" 
                                 id="stamp-<?php echo strtolower($countryData['name_en']); ?>">
                                <div class="text-lg text-gray-300" id="icon-<?php echo strtolower($countryData['name_en']); ?>"><?php echo $countryData['emoji']; ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- 統計情報 -->
        <div class="mt-8 bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-6">
            <div class="text-center">
                <h3 class="text-xl font-bold text-gray-800 mb-4">リアルタイム統計情報</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600"><?php echo $total_countries; ?></div>
                        <div class="text-sm text-gray-600">表示国数</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600"><?php echo number_format($total_population); ?></div>
                        <div class="text-sm text-gray-600">総人口</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600" id="stampedCount">0</div>
                        <div class="text-sm text-gray-600">押したスタンプ</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-600"><?php echo $total_countries; ?></div>
                        <div class="text-sm text-gray-600">総スタンプ数</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- API情報表示 -->
        <div class="mt-8 bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-6">
            <div class="text-center">
                <h3 class="text-xl font-bold text-gray-800 mb-4">API情報</h3>
                <p class="text-sm text-gray-600 mb-2">このページは REST Countries API を使用してリアルタイムで国情報を取得しています</p>
                <div class="flex justify-center space-x-4 text-xs text-gray-500">
                    <span><i class="fas fa-database mr-1"></i>データソース: REST Countries API</span>
                    <span><i class="fas fa-clock mr-1"></i>更新頻度: 1時間</span>
                    <span><i class="fas fa-globe mr-1"></i>対応国数: 250+</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        let stampedCountries = [];

        function stampCountry(country) {
            const stampElement = document.getElementById(`stamp-${country}`);
            const iconElement = document.getElementById(`icon-${country}`);
            
            if (!stampedCountries.includes(country)) {
                stampedCountries.push(country);
                stampElement.classList.add('stamped');
                iconElement.classList.remove('text-gray-300');
                iconElement.classList.add('text-yellow-600');
                
                // スタンプカウントを更新
                document.getElementById('stampedCount').textContent = stampedCountries.length;
                
                // 成功メッセージ
                alert(`${country}のスタンプが押されました！`);
            } else {
                alert('このスタンプは既に押されています！');
            }
        }

        // ページ読み込み時にローカルストレージからスタンプ情報を復元
        window.addEventListener('load', function() {
            const savedStamps = localStorage.getItem('passport_stamps');
            if (savedStamps) {
                stampedCountries = JSON.parse(savedStamps);
                stampedCountries.forEach(country => {
                    const stampElement = document.getElementById(`stamp-${country}`);
                    const iconElement = document.getElementById(`icon-${country}`);
                    if (stampElement && iconElement) {
                        stampElement.classList.add('stamped');
                        iconElement.classList.remove('text-gray-300');
                        iconElement.classList.add('text-yellow-600');
                    }
                });
                document.getElementById('stampedCount').textContent = stampedCountries.length;
            }
        });

        // スタンプ情報をローカルストレージに保存
        function saveStamps() {
            localStorage.setItem('passport_stamps', JSON.stringify(stampedCountries));
        }

        // ページ離脱時に保存
        window.addEventListener('beforeunload', saveStamps);
    </script>
</body>
</html> 