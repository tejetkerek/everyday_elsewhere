<?php
// URLパラメータから国を取得
$country = $_GET['country'] ?? 'japan';

// 国とテーマのデータ
$countryData = [
    'japan' => [
        'name' => '日本',
        'name_en' => 'Japan',
        'flag' => '🇯🇵',
        'language' => '日本語',
        'hello' => 'こんにちは',
        'themes' => [
            'breakfast' => [
                'title' => '世界の朝ごはん',
                'subtitle' => 'お味噌汁とご飯で一日をスタート！',
                'icon' => '🍚',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&h=600&fit=crop',
                'description' => '日本の朝ごはんは、お味噌汁とご飯が基本です。納豆や焼き魚、漬物も一緒に食べます。栄養たっぷりで、学校やお仕事に行く前に元気になれる朝ごはんです。',
                'fun_fact' => 'お味噌汁は、お母さんやおばあちゃんの味が一番美味しいと言われています。'
            ],
            'weather' => [
                'title' => '天候と自然',
                'subtitle' => '四季折々の美しい自然',
                'icon' => '🌸',
                'image' => 'https://images.unsplash.com/photo-1545569341-9eb8b30979d9?w=800&h=600&fit=crop',
                'description' => '日本には春、夏、秋、冬の四季があります。春は桜、夏は緑、秋は紅葉、冬は雪景色が美しいです。富士山や温泉も日本の自然の宝物です。',
                'fun_fact' => '桜の花は、春の訪れを告げる日本の象徴です。'
            ],
            'capital' => [
                'title' => '首都と観光地',
                'subtitle' => '東京の魅力を探検しよう',
                'icon' => '🗼',
                'image' => 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=800&h=600&fit=crop',
                'description' => '東京は日本の首都です。東京タワーやスカイツリー、浅草寺など、古いものと新しいものが混在する魅力的な街です。',
                'fun_fact' => '東京タワーは、パリのエッフェル塔を参考に作られました。'
            ],
            'language' => [
                'title' => '言葉と「おはようございます」',
                'subtitle' => '日本語のあいさつ文化',
                'icon' => '🗣️',
                'image' => 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=800&h=600&fit=crop',
                'description' => '日本では、お辞儀をしてあいさつします。朝は「おはようございます」、昼は「こんにちは」、夜は「こんばんは」。深くお辞儀するほど、相手への敬意を表します。',
                'fun_fact' => 'お辞儀の角度は、相手との関係によって変わります。'
            ],
            'culture' => [
                'title' => '民族と宗教',
                'subtitle' => '神道と仏教の文化',
                'icon' => '⛩️',
                'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'description' => '日本には神道と仏教の文化があります。神社やお寺は、人々の心のよりどころです。お正月やお盆など、伝統的な行事も大切にされています。',
                'fun_fact' => '神社では、手を洗ってからお参りするのがマナーです。'
            ],
            'sports' => [
                'title' => '人気のスポーツ',
                'subtitle' => '相撲と野球が大人気！',
                'icon' => '⚾',
                'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop',
                'description' => '日本では野球と相撲がとても人気です。野球は学校でも盛んで、相撲は日本の伝統スポーツです。サッカーも子供たちに人気があります。',
                'fun_fact' => '相撲の力士は、髷（まげ）を結っています。'
            ]
        ]
    ],
    'egypt' => [
        'name' => 'エジプト',
        'name_en' => 'Egypt',
        'flag' => '🇪🇬',
        'language' => 'アラビア語',
        'hello' => 'مرحبا (マルハバ)',
        'themes' => [
            'breakfast' => [
                'title' => '世界の朝ごはん',
                'subtitle' => 'フールとタヒーナでエネルギーチャージ！',
                'icon' => '🥙',
                'image' => 'https://images.unsplash.com/photo-1515669097368-22e68427d265?w=800&h=600&fit=crop',
                'description' => 'エジプトの朝ごはんは、フール（豆のペースト）とタヒーナ（ごまペースト）が人気です。フラットブレッドと一緒に食べて、一日のエネルギーを補給します。',
                'fun_fact' => 'フールは、古代エジプト時代から食べられている伝統的な朝食です。'
            ],
            'weather' => [
                'title' => '天候と自然',
                'subtitle' => '砂漠とナイル川の国',
                'icon' => '🏜️',
                'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'description' => 'エジプトは砂漠の国です。ナイル川が流れ、ピラミッドやスフィンクスがあります。夏はとても暑く、冬は過ごしやすい気候です。',
                'fun_fact' => 'ナイル川は、世界で一番長い川です。'
            ],
            'capital' => [
                'title' => '首都と観光地',
                'subtitle' => 'カイロとピラミッド',
                'icon' => '🏛️',
                'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'description' => 'カイロはエジプトの首都です。ギザのピラミッドやスフィンクス、エジプト博物館があります。古代文明の神秘的な魅力が詰まっています。',
                'fun_fact' => 'ピラミッドは、古代エジプトの王様のお墓です。'
            ],
            'language' => [
                'title' => '言葉と「おはようございます」',
                'subtitle' => 'アラビア語のあいさつ',
                'icon' => '🗣️',
                'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=600&fit=crop',
                'description' => 'エジプトではアラビア語を話します。「マルハバ」は「こんにちは」という意味です。握手をしてあいさつし、親しい人には頬にキスをすることもあります。',
                'fun_fact' => 'アラビア語は、右から左に書きます。'
            ],
            'culture' => [
                'title' => '民族と宗教',
                'subtitle' => 'イスラム教の文化',
                'icon' => '🕌',
                'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'description' => 'エジプトではイスラム教が主な宗教です。モスク（お寺）があり、一日に5回お祈りをします。ラマダンという断食の期間もあります。',
                'fun_fact' => 'モスクの塔からは、お祈りの時間を知らせる声が聞こえます。'
            ],
            'sports' => [
                'title' => '人気のスポーツ',
                'subtitle' => 'サッカーが大人気！',
                'icon' => '⚽',
                'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop',
                'description' => 'エジプトではサッカーがとても人気です。エジプト代表チームはアフリカで強く、多くの人がサッカーを楽しんでいます。',
                'fun_fact' => 'エジプトのサッカー選手は、アフリカで一番有名です。'
            ]
        ]
    ],
    'brazil' => [
        'name' => 'ブラジル',
        'name_en' => 'Brazil',
        'flag' => '🇧🇷',
        'language' => 'ポルトガル語',
        'hello' => 'Olá (オラー)',
        'themes' => [
            'breakfast' => [
                'title' => '世界の朝ごはん',
                'subtitle' => 'パンとコーヒーで始まる一日！',
                'icon' => '☕',
                'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=800&h=600&fit=crop',
                'description' => 'ブラジルの朝ごはんは、パンとコーヒーが基本です。フルーツやチーズも一緒に食べて、元気に一日をスタートします。',
                'fun_fact' => 'ブラジルは世界で一番コーヒーを飲む国です。'
            ],
            'weather' => [
                'title' => '天候と自然',
                'subtitle' => 'アマゾンと熱帯雨林',
                'icon' => '🌴',
                'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'description' => 'ブラジルは熱帯の国です。アマゾンの熱帯雨林があり、たくさんの動物や植物が住んでいます。海岸線も長く、美しいビーチがあります。',
                'fun_fact' => 'アマゾンは、世界で一番大きな熱帯雨林です。'
            ],
            'capital' => [
                'title' => '首都と観光地',
                'subtitle' => 'ブラジリアとリオデジャネイロ',
                'icon' => '🗽',
                'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'description' => 'ブラジルの首都はブラジリアです。リオデジャネイロにはコルコバードのキリスト像があります。サンバの祭りも有名です。',
                'fun_fact' => 'コルコバードのキリスト像は、リオデジャネイロのシンボルです。'
            ],
            'language' => [
                'title' => '言葉と「おはようございます」',
                'subtitle' => 'ポルトガル語のあいさつ',
                'icon' => '🗣️',
                'image' => 'https://images.unsplash.com/photo-1516589178581-6cd7833ae3b2?w=800&h=600&fit=crop',
                'description' => 'ブラジルではポルトガル語を話します。「オラー」は「こんにちは」という意味です。ハグをしてあいさつし、親しい人には頬にキスをすることもあります。',
                'fun_fact' => 'ブラジルは、南アメリカで一番大きな国です。'
            ],
            'culture' => [
                'title' => '民族と宗教',
                'subtitle' => 'カトリックと多様な文化',
                'icon' => '⛪',
                'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'description' => 'ブラジルではカトリックが主な宗教です。アフリカやヨーロッパ、先住民の文化が混ざり合って、豊かな文化を作っています。',
                'fun_fact' => 'ブラジルには、世界で一番大きなカーニバルがあります。'
            ],
            'sports' => [
                'title' => '人気のスポーツ',
                'subtitle' => 'サッカーの王様ペレの国',
                'icon' => '⚽',
                'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop',
                'description' => 'ブラジルはサッカーの国です。ペレやロナウドなど、世界で有名な選手をたくさん輩出しています。サッカーは子供から大人まで大人気です。',
                'fun_fact' => 'ブラジルは、ワールドカップで一番多く優勝した国です。'
            ]
        ]
    ]
];

// 現在の国のデータを取得
$currentCountry = $countryData[$country] ?? $countryData['japan'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $currentCountry['name']; ?>の学習 - 世界の小さな日常パスポート</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .theme-card {
            transition: all 0.3s ease;
        }
        .theme-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .stamp-animation {
            animation: stamp 0.5s ease-in-out;
        }
        @keyframes stamp {
            0% { transform: scale(0.5) rotate(-10deg); opacity: 0; }
            50% { transform: scale(1.2) rotate(5deg); opacity: 0.8; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-purple-50 min-h-screen">
    <!-- ヘッダー -->
    <header class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-6xl mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-3xl"><?php echo $currentCountry['flag']; ?></span>
                    <h1 class="text-2xl font-bold text-gray-800"><?php echo $currentCountry['name']; ?>の学習</h1>
                </div>
                <div class="flex space-x-3">
                    <a href="country_select.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-globe mr-1"></i>
                        地球儀に戻る
                    </a>
                    <a href="dashboard.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-home mr-1"></i>
                        ダッシュボード
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- 国情報 -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex items-center space-x-4">
                <div class="text-6xl"><?php echo $currentCountry['flag']; ?></div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-800"><?php echo $currentCountry['name']; ?> (<?php echo $currentCountry['name_en']; ?>)</h2>
                    <div class="flex items-center space-x-4 text-gray-600 mt-2">
                        <span><i class="fas fa-language mr-1"></i><?php echo $currentCountry['language']; ?></span>
                        <span><i class="fas fa-hand-wave mr-1"></i><?php echo $currentCountry['hello']; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- テーマ一覧 -->
        <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">学習テーマを選ぼう</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($currentCountry['themes'] as $key => $theme): ?>
                <div class="theme-card bg-white rounded-2xl shadow-lg overflow-hidden cursor-pointer" onclick="openTheme('<?php echo $key; ?>')">
                    <div class="h-48 bg-gradient-to-r from-blue-400 to-purple-500 relative">
                        <img src="<?php echo $theme['image']; ?>" 
                             alt="<?php echo $theme['title']; ?>" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                        <div class="absolute top-4 right-4 text-4xl"><?php echo $theme['icon']; ?></div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-2"><?php echo $theme['title']; ?></h4>
                        <p class="text-gray-600 text-sm mb-4"><?php echo $theme['subtitle']; ?></p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-blue-600 font-semibold">読んでスタンプを獲得</span>
                            <i class="fas fa-arrow-right text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 進捗状況 -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">学習進捗</h3>
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                <?php foreach ($currentCountry['themes'] as $key => $theme): ?>
                <div class="text-center">
                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-2" id="progress-<?php echo $key; ?>">
                        <span class="text-lg"><?php echo $theme['icon']; ?></span>
                    </div>
                    <div class="text-xs text-gray-600"><?php echo $theme['title']; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- テーマ詳細モーダル -->
    <div id="themeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div id="modalContent">
                <!-- モーダルコンテンツがここに動的に挿入されます -->
            </div>
        </div>
    </div>

    <!-- スタンプ成功メッセージ -->
    <div id="stampSuccess" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 text-center max-w-sm mx-4">
            <div class="text-6xl mb-4 stamp-animation">🛂</div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">スタンプ獲得！</h3>
            <p class="text-gray-600 mb-6" id="stampMessage">テーマのスタンプがパスポートに押されました。</p>
            <button onclick="closeStampSuccess()" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                閉じる
            </button>
        </div>
    </div>

    <script>
        const countryData = <?php echo json_encode($currentCountry); ?>;
        let currentTheme = null;

        // テーマを開く
        function openTheme(themeKey) {
            const theme = countryData.themes[themeKey];
            currentTheme = themeKey;
            
            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = `
                <div class="relative">
                    <div class="h-64 bg-gradient-to-r from-blue-400 to-purple-500 relative">
                        <img src="${theme.image}" alt="${theme.title}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                            <h2 class="text-3xl font-bold mb-2">${theme.title}</h2>
                            <p class="text-lg opacity-90">${theme.subtitle}</p>
                        </div>
                        <button onclick="closeTheme()" class="absolute top-4 right-4 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-full w-10 h-10 flex items-center justify-center">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="prose prose-lg max-w-none">
                            <p class="text-gray-700 leading-relaxed text-lg mb-6">
                                ${theme.description}
                            </p>
                            
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg mb-6">
                                <div class="flex items-start">
                                    <i class="fas fa-lightbulb text-yellow-500 mt-1 mr-3"></i>
                                    <div>
                                        <h3 class="font-semibold text-yellow-800 mb-1">おもしろ豆知識</h3>
                                        <p class="text-yellow-700">${theme.fun_fact}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-4">
                            <button onclick="stampPassport()" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-xl transition-colors">
                                <i class="fas fa-stamp mr-2"></i>
                                パスポートにスタンプを押す
                            </button>
                            <button onclick="saveToList()" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-xl transition-colors">
                                <i class="fas fa-bookmark mr-2"></i>
                                あとで教えるリストに保存
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('themeModal').classList.remove('hidden');
        }

        // テーマを閉じる
        function closeTheme() {
            document.getElementById('themeModal').classList.add('hidden');
        }

        // パスポートにスタンプを押す
        function stampPassport() {
            if (currentTheme) {
                // 進捗を更新
                const progressElement = document.getElementById(`progress-${currentTheme}`);
                progressElement.classList.remove('bg-gray-200');
                progressElement.classList.add('bg-green-500');
                
                // パスポートにスタンプを追加
                addStampToPassport(currentTheme);
                
                // スタンプ成功メッセージを表示
                const stampMessage = document.getElementById('stampMessage');
                const theme = countryData.themes[currentTheme];
                stampMessage.textContent = `${theme.title}のスタンプがパスポートに押されました。`;
                
                document.getElementById('stampSuccess').classList.remove('hidden');
                document.getElementById('themeModal').classList.add('hidden');
            }
        }

        // パスポートにスタンプを追加する関数
        function addStampToPassport(themeKey) {
            // ローカルストレージにスタンプ情報を保存
            const stamps = JSON.parse(localStorage.getItem('passport_stamps') || '{}');
            const currentCountry = '<?php echo $currentCountry['name']; ?>';
            
            if (!stamps[currentCountry]) {
                stamps[currentCountry] = [];
            }
            
            if (!stamps[currentCountry].includes(themeKey)) {
                stamps[currentCountry].push(themeKey);
                localStorage.setItem('passport_stamps', JSON.stringify(stamps));
            }
        }

        // リストに保存
        function saveToList() {
            if (currentTheme) {
                const theme = countryData.themes[currentTheme];
                alert(`${theme.title}をあとで教えるリストに保存しました！`);
            }
        }

        // スタンプ成功メッセージを閉じる
        function closeStampSuccess() {
            document.getElementById('stampSuccess').classList.add('hidden');
        }

        // モーダル外クリックで閉じる
        document.getElementById('themeModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTheme();
            }
        });

        // キーボードショートカット
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeTheme();
                closeStampSuccess();
            }
        });
    </script>
</body>
</html> 