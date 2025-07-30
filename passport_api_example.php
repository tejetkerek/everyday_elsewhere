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

// パスポートに表示する国のリスト
$passportCountries = [
    'Japan' => '日本',
    'Vietnam' => 'ベトナム',
    'France' => 'フランス',
    'Italy' => 'イタリア',
    'Brazil' => 'ブラジル',
    'Egypt' => 'エジプト',
    'India' => 'インド',
    'South Korea' => '韓国',
    'Australia' => 'オーストラリア'
];

// 各国の情報を取得
$countriesData = [];
foreach ($passportCountries as $enName => $jpName) {
    $countryData = getCountryDataForPassport($enName);
    if ($countryData) {
        $countriesData[$jpName] = $countryData;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスポート（API版） - 世界の小さな日常</title>
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
    </style>
</head>
<body class="bg-gradient-to-br from-blue-400 to-purple-600 min-h-screen">
    <!-- ヘッダー -->
    <header class="bg-white/90 backdrop-blur-sm shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="text-3xl">📖</div>
                    <h1 class="text-xl font-bold text-gray-800">パスポート（API版）</h1>
                </div>
                <a href="dashboard.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-globe mr-2"></i>地球儀に戻る
                </a>
            </div>
        </div>
    </header>

    <!-- メインコンテンツ -->
    <div class="container mx-auto px-4 py-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white mb-4">リアルタイム国情報</h2>
            <p class="text-white/90 text-lg">APIから取得した最新の国情報を表示しています</p>
        </div>

        <!-- 国情報カード -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($countriesData as $jpName => $countryData): ?>
            <div class="country-card bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-6">
                <div class="text-center mb-4">
                    <div class="text-4xl mb-2"><?php echo $countryData['flag']; ?></div>
                    <h3 class="text-xl font-bold text-gray-800"><?php echo $countryData['name']; ?></h3>
                    <p class="text-sm text-gray-600"><?php echo $countryData['name_en']; ?></p>
                </div>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-2 bg-blue-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">首都</span>
                        <span class="text-sm font-bold text-blue-600"><?php echo $countryData['capital']; ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between p-2 bg-green-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">人口</span>
                        <span class="text-sm font-bold text-green-600"><?php echo $countryData['population']; ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between p-2 bg-purple-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">地域</span>
                        <span class="text-sm font-bold text-purple-600"><?php echo $countryData['region']; ?></span>
                    </div>
                    
                    <?php if ($countryData['area'] > 0): ?>
                    <div class="flex items-center justify-between p-2 bg-orange-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">面積</span>
                        <span class="text-sm font-bold text-orange-600"><?php echo number_format($countryData['area']); ?> km²</span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($countryData['languages'])): ?>
                    <div class="flex items-center justify-between p-2 bg-red-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">言語</span>
                        <span class="text-sm font-bold text-red-600">
                            <?php echo implode(', ', array_values($countryData['languages'])); ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="mt-4 text-center">
                    <button onclick="showCountryDetails('<?php echo $countryData['name']; ?>')" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-sm">
                        <i class="fas fa-info-circle mr-1"></i>詳細を見る
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- 統計情報 -->
        <div class="mt-8 bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-6">
            <div class="text-center">
                <h3 class="text-xl font-bold text-gray-800 mb-4">統計情報</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600"><?php echo count($countriesData); ?></div>
                        <div class="text-sm text-gray-600">表示国数</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">
                            <?php 
                            $totalPopulation = 0;
                            foreach ($countriesData as $country) {
                                $totalPopulation += (int)str_replace(',', '', $country['population']);
                            }
                            echo number_format($totalPopulation);
                            ?>
                        </div>
                        <div class="text-sm text-gray-600">総人口</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">
                            <?php 
                            $regions = array_unique(array_column($countriesData, 'region'));
                            echo count($regions);
                            ?>
                        </div>
                        <div class="text-sm text-gray-600">地域数</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-600">
                            <?php 
                            $totalArea = 0;
                            foreach ($countriesData as $country) {
                                $totalArea += $country['area'];
                            }
                            echo number_format($totalArea);
                            ?>
                        </div>
                        <div class="text-sm text-gray-600">総面積(km²)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showCountryDetails(countryName) {
            alert(`${countryName}の詳細情報を表示します。\n\nこの機能は今後実装予定です。`);
        }
    </script>
</body>
</html> 