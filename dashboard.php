<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ダッシュボード - 世界の小さな日常</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50/30 to-purple-50/30 h-screen overflow-hidden">
    <!-- ヘッダー -->
    <header class="bg-gradient-to-r from-blue-500 to-purple-600 shadow-lg">
        <div class="w-full max-w-6xl mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="text-4xl animate-pulse">🌎</div>
                    <h1 class="text-2xl font-bold text-white">世界の小さな日常パスポート</h1>
                </div>
                <div class="flex items-center space-x-3">
                    <?php
                    // ログイン状態をチェック
                    if (isset($_SESSION['chk_ssid']) && $_SESSION['chk_ssid'] === session_id()) {
                        // 管理者権限がある場合
                        if (isset($_SESSION['user_email']) && $_SESSION['user_email'] === 'admin@system') {
                            echo '<div class="flex items-center space-x-2">';
                            echo '<div class="flex items-center space-x-2 bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">';
                            echo '<div class="w-2 h-2 bg-purple-500 rounded-full animate-pulse"></div>';
                            echo '<span class="font-medium">管理者としてログイン中</span>';
                            echo '</div>';
                            echo '<a href="passport.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">';
                            echo '<i class="fas fa-passport mr-1"></i>';
                            echo 'マイパスポート';
                            echo '</a>';
                            echo '<a href="user_userlist.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">';
                            echo '<i class="fas fa-users-cog mr-1"></i>';
                            echo 'ユーザー管理';
                            echo '</a>';
                            echo '<a href="user_logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">';
                            echo '<i class="fas fa-sign-out-alt mr-1"></i>';
                            echo 'ログアウト';
                            echo '</a>';
                            echo '</div>';
                        } else {
                            // 一般ユーザーとしてログイン中の場合
                            echo '<div class="flex items-center space-x-2">';
                            echo '<div class="flex items-center space-x-2 bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">';
                            echo '<div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>';
                            echo '<span class="font-medium">ログイン中</span>';
                            echo '</div>';
                            echo '<a href="passport.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">';
                            echo '<i class="fas fa-passport mr-1"></i>';
                            echo 'マイパスポート';
                            echo '</a>';
                            echo '<a href="user_logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">';
                            echo '<i class="fas fa-sign-out-alt mr-1"></i>';
                            echo 'ログアウト';
                            echo '</a>';
                            echo '</div>';
                        }
                    } else {
                        // ログインしていない場合
                        echo '<div class="flex items-center space-x-2">';
                        echo '<div class="flex items-center space-x-2 bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm">';
                        echo '<div class="w-2 h-2 bg-gray-400 rounded-full"></div>';
                        echo '<span>未ログイン</span>';
                        echo '</div>';
                        echo '<a href="user_login.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">';
                        echo '<i class="fas fa-sign-in-alt mr-1"></i>';
                        echo 'ログイン';
                        echo '</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </header>

    <div class="flex-1 relative" style="height: calc(100vh - 100px); overflow: hidden;">
        <!-- 地球儀表示エリア（全画面） -->
        <div id="globeViz" style="height: 100%; width: 100%; position: relative;">
            <div class="loading absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-gray-600" id="loading">
                <div class="text-center">
                    <div class="text-4xl mb-2">🌍</div>
                    <div>地球儀を読み込み中...</div>
                </div>
            </div>
        </div>

        <!-- 国情報パネル -->
        <div class="country-info absolute top-4 left-4 bg-white rounded-lg shadow-lg p-4 max-w-sm" id="countryInfo" style="display: none; z-index: 1000;">
            <h4 class="text-lg font-bold text-gray-800 mb-2" id="countryName">国名</h4>
            <p class="text-sm text-gray-600 mb-3" id="countryDescription">国の説明</p>
            <div class="flex space-x-2">
                <button id="exploreBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors">
                    <i class="fas fa-book mr-1"></i>
                    探検する
                </button>
                <button onclick="hideCountryInfo()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg text-sm transition-colors">
                    閉じる
                </button>
            </div>
        </div>

        <!-- 地域インストラクションパネル -->
        <div class="region-info absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-lg shadow-lg p-4 max-w-xs" id="regionInfo" style="display: none; z-index: 1000;">
            <div class="flex items-center space-x-2 mb-2">
                <div class="text-2xl">🗺️</div>
                <h4 class="text-lg font-bold text-gray-800">現在の地域</h4>
            </div>
            <p class="text-sm text-gray-600" id="regionName">地域名</p>
            <p class="text-xs text-gray-500 mt-1" id="regionDescription">地域の説明</p>
        </div>

        <!-- ベトナム情報パネル（右側） -->
        <div class="absolute top-4 right-4 bg-white rounded-2xl shadow-lg p-6 max-w-sm border border-blue-200" id="vietnamChat" style="z-index: 1000; height: 80vh; overflow-y: auto;">
            <!-- カバを持ったベトナムキャラクター -->
            <div class="text-center mb-4">
                <div class="relative inline-block">
                    <div class="text-6xl mb-2">🇻🇳</div>
                    <div class="absolute -top-2 -right-2 text-2xl">🎩</div>
                </div>
            </div>
            
            <!-- 初期状態：トピック選択 -->
            <div id="topicSelection" class="mb-3">
                <div class="text-center mb-3">
                    <div class="text-sm font-medium text-gray-800 mb-1" id="vietnamTitle">こんにちは！ベトナムについて知りたいことがあるかな？</div>
                    <p class="text-gray-600 text-xs leading-relaxed" id="vietnamMessage">
                        僕がベトナムのことを教えてあげるよ！どんなことを知りたい？最後には朝ごはんも一緒に食べてみよう！
                    </p>
                </div>
                <div class="space-y-1 mb-2">
                    <button onclick="showVietnamInfo('population')" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs transition-colors">
                        <i class="fas fa-users mr-1"></i>人口
                    </button>
                    <button onclick="showVietnamInfo('capital')" class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-xs transition-colors">
                        <i class="fas fa-building mr-1"></i>首都
                    </button>
                    <button onclick="showVietnamInfo('language')" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded text-xs transition-colors">
                        <i class="fas fa-language mr-1"></i>言語
                    </button>
                    <button onclick="showVietnamInfo('religion')" class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs transition-colors">
                        <i class="fas fa-pray mr-1"></i>宗教
                    </button>
                    <button onclick="showVietnamInfo('ethnicity')" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded text-xs transition-colors">
                        <i class="fas fa-users-cog mr-1"></i>民族
                    </button>
                    <button onclick="showVietnamInfo('climate')" class="w-full bg-pink-600 hover:bg-pink-700 text-white px-3 py-1.5 rounded text-xs transition-colors">
                        <i class="fas fa-cloud-sun mr-1"></i>気候
                    </button>
                </div>
                <div class="text-center mt-4">
                    <button onclick="showVietnamBreakfast()" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-1.5 rounded-lg text-xs transition-colors">
                        <i class="fas fa-utensils mr-1"></i>朝ごはんを食べてみる
                    </button>
                </div>
            </div>
            
            <!-- 会話状態：選択後に表示 -->
            <div id="conversationMode" class="hidden">
                <div class="conversation-container mb-4" style="height: 40vh; overflow-y: auto;">
                    <div class="space-y-4" id="conversationMessages">
                        <!-- 会話メッセージがここに追加される -->
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button onclick="resetVietnamChat()" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded text-xs transition-colors">
                        <i class="fas fa-undo mr-1"></i>新しい話題
                    </button>
                    <button onclick="nextMessage()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-xs transition-colors">
                        <i class="fas fa-arrow-right mr-1"></i>次へ
                    </button>
                </div>
            </div>
        </div>



        <!-- 日本からの基本情報パネル（左中央） -->
        <div class="absolute top-1/2 left-8 transform -translate-y-1/2 bg-white/95 backdrop-blur-sm rounded-xl shadow-lg p-4 max-w-xs border border-green-200" id="japanInfo" style="z-index: 1000;">
            <div class="text-center mb-3">
                <div class="text-2xl mb-1">🇯🇵</div>
                <h3 class="text-sm font-bold text-gray-800">日本からの情報</h3>
            </div>
            <div class="space-y-2">
                <div class="flex items-center justify-between p-2 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-blue-600 mr-1 text-xs"></i>
                        <span class="text-xs font-medium text-gray-700">距離</span>
                    </div>
                    <span class="text-xs font-bold text-blue-600" id="distance">約3,200km</span>
                </div>
                <div class="flex items-center justify-between p-2 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-plane text-green-600 mr-1 text-xs"></i>
                        <span class="text-xs font-medium text-gray-700">飛行時間</span>
                    </div>
                    <span class="text-xs font-bold text-green-600" id="flightTime">約5時間</span>
                </div>
                <div class="flex items-center justify-between p-2 bg-purple-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-purple-600 mr-1 text-xs"></i>
                        <span class="text-xs font-medium text-gray-700">時差</span>
                    </div>
                    <span class="text-xs font-bold text-purple-600" id="timeDiff">-2時間</span>
                </div>
                <div class="flex items-center justify-between p-2 bg-orange-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-yen-sign text-orange-600 mr-1 text-xs"></i>
                        <span class="text-xs font-medium text-gray-700">通貨</span>
                    </div>
                    <span class="text-xs font-bold text-orange-600" id="currency">ドン（VND）</span>
                </div>
                <div class="flex items-center justify-between p-2 bg-red-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-thermometer-half text-red-600 mr-1 text-xs"></i>
                        <span class="text-xs font-medium text-gray-700">気温</span>
                    </div>
                    <span class="text-xs font-bold text-red-600" id="temperature">20-35°C</span>
                </div>
            </div>
        </div>

    </div>






    </div>

    <!-- 地球儀用スクリプト -->
    <script src="https://unpkg.com/three@0.150.1/build/three.min.js"></script>
    <script src="https://unpkg.com/globe.gl@2.32.0/dist/globe.gl.min.js"></script>
    <script>
        // 国データ
        const countries = [
            {
                name: '日本',
                name_en: 'Japan',
                flag: '🇯🇵',
                lat: 35.6762,
                lng: 139.6503,
                description: 'お味噌汁とご飯の朝ごはん、お辞儀のあいさつ文化。富士山と桜の美しい国。',
                color: '#ff0000'
            },
            {
                name: 'エジプト',
                name_en: 'Egypt',
                flag: '🇪🇬',
                lat: 30.0444,
                lng: 31.2357,
                description: 'ピラミッドとスフィンクス、ナイル川。古代文明の神秘的な国。',
                color: '#ff6600'
            },
            {
                name: 'ブラジル',
                name_en: 'Brazil',
                flag: '🇧🇷',
                lat: -15.7801,
                lng: -47.9292,
                description: 'サンバとサッカー、アマゾンの熱帯雨林。情熱的な南米の国。',
                color: '#00ff00'
            },
            {
                name: 'フランス',
                name_en: 'France',
                flag: '🇫🇷',
                lat: 48.8566,
                lng: 2.3522,
                description: 'エッフェル塔とルーブル美術館、クロワッサンとワインの国。',
                color: '#0000ff'
            },
            {
                name: 'イタリア',
                name_en: 'Italy',
                flag: '🇮🇹',
                lat: 41.9028,
                lng: 12.4964,
                description: 'ピザとパスタ、コロッセオとヴェネツィア。芸術と美食の国。',
                color: '#8000ff'
            },
            {
                name: 'インド',
                name_en: 'India',
                flag: '🇮🇳',
                lat: 28.6139,
                lng: 77.2090,
                description: 'カレーとヨガ、タージマハルとガンジス川。多様な文化の国。',
                color: '#ff8000'
            },
            {
                name: '韓国',
                name_en: 'South Korea',
                flag: '🇰🇷',
                lat: 37.5665,
                lng: 126.9780,
                description: 'キムチとK-POP、韓国料理と伝統文化。現代と伝統が融合する国。',
                color: '#ff0080'
            },
            {
                name: 'オーストラリア',
                name_en: 'Australia',
                flag: '🇦🇺',
                lat: -33.8688,
                lng: 151.2093,
                description: 'コアラとカンガルー、グレートバリアリーフ。自然豊かな大陸。',
                color: '#00ffff'
            }
        ];

        let selectedCountry = null;
        let globe = null;
        let currentRegion = null;

        // 地域データ
        const regions = [
            {
                name: 'アジア',
                description: '日本、中国、韓国、インドなど、多様な文化を持つ地域',
                bounds: { minLat: 10, maxLat: 50, minLng: 70, maxLng: 140 },
                color: '#ef4444'
            },
            {
                name: 'ヨーロッパ',
                description: 'フランス、イタリア、ドイツなど、歴史と芸術の地域',
                bounds: { minLat: 40, maxLat: 60, minLng: -10, maxLng: 30 },
                color: '#3b82f6'
            },
            {
                name: 'アフリカ',
                description: 'エジプト、ケニア、南アフリカなど、大自然の地域',
                bounds: { minLat: -35, maxLat: 35, minLng: -20, maxLng: 50 },
                color: '#f59e0b'
            },
            {
                name: '北アメリカ',
                description: 'アメリカ、カナダ、メキシコなど、多様な文化の地域',
                bounds: { minLat: 25, maxLat: 60, minLng: -130, maxLng: -60 },
                color: '#10b981'
            },
            {
                name: '南アメリカ',
                description: 'ブラジル、アルゼンチン、ペルーなど、情熱的な地域',
                bounds: { minLat: -50, maxLat: 10, minLng: -80, maxLng: -40 },
                color: '#8b5cf6'
            },
            {
                name: 'オセアニア',
                description: 'オーストラリア、ニュージーランドなど、自然豊かな地域',
                bounds: { minLat: -45, maxLat: -10, minLng: 110, maxLng: 180 },
                color: '#06b6d4'
            }
        ];

        // エラーハンドリング
        window.addEventListener('error', function(e) {
            console.error('JavaScript error:', e.error);
            document.getElementById('loading').innerHTML = 'エラーが発生しました。ページを再読み込みしてください。';
        });

        // 地球儀の初期化
        function initGlobe() {
            try {
                const container = document.getElementById('globeViz');
                globe = Globe()
                    (container)
                    .globeImageUrl('//unpkg.com/three-globe/example/img/earth-blue-marble.jpg')
                    .backgroundColor('#f0f4f8')
                    .showAtmosphere(true)
                    .atmosphereColor('#3a7ca5')
                    .atmosphereAltitude(0.15)
                    .width(container.offsetWidth)
                    .height(container.offsetHeight)
                    .enablePointerInteraction(true)
                    .enableMouseNavigation(true)
                    .enableTouchNavigation(true)
                    .enableZoom(false);

                // ポイントデータを設定
                globe
                    .pointsData(countries)
                    .pointLat('lat')
                    .pointLng('lng')
                    .pointColor('color')
                    .pointAltitude(0.05)
                    .pointRadius(2.0)
                    .pointLabel('name')
                    .onPointClick(handleCountryClick);

                // 地球儀の回転を監視して地域を判定（間隔を長くして負荷を軽減）
                let lastRegionCheck = 0;
                globe.onGlobeRotate(() => {
                    const now = Date.now();
                    if (now - lastRegionCheck > 1000) { // 1秒間隔でチェック
                        lastRegionCheck = now;
                        const coords = globe.pointOfView();
                        const region = getCurrentRegion(coords.lat, coords.lng);
                        if (region && region !== currentRegion) {
                            currentRegion = region;
                            showRegionInfo(region);
                        }
                    }
                });

                // ホイールイベントを無効化
                container.addEventListener('wheel', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }, { passive: false });

                // さらに強力なホイール無効化
                container.addEventListener('wheel', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }, { capture: true, passive: false });

                // CSSでもホイールを無効化
                container.style.overflow = 'hidden';

                // 初期地域を表示
                setTimeout(() => {
                    const initialCoords = globe.pointOfView();
                    const initialRegion = getCurrentRegion(initialCoords.lat, initialCoords.lng);
                    if (initialRegion) {
                        currentRegion = initialRegion;
                        showRegionInfo(initialRegion);
                    }
                }, 2000);

                // 読み込み完了
                document.getElementById('loading').style.display = 'none';

                // ウィンドウリサイズ対応
                window.addEventListener('resize', () => {
                    globe.width(container.offsetWidth).height(container.offsetHeight);
                });

            } catch (error) {
                console.error('Globe initialization error:', error);
                document.getElementById('loading').innerHTML = '地球儀の読み込みに失敗しました。';
            }
        }

        // 国クリック時の処理
        function handleCountryClick(country) {
            console.log('国がクリックされました:', country);
            selectedCountry = country;
            showCountryInfo(country);
            updateJapanInfo(country.name); // 日本からの情報を更新
        }

        // 国情報を表示
        function showCountryInfo(country) {
            console.log('国情報を表示:', country);
            document.getElementById('countryName').textContent = country.flag + ' ' + country.name;
            document.getElementById('countryDescription').textContent = country.description;
            document.getElementById('countryInfo').style.display = 'block';
            
            // マスコットキャラの会話を更新
            updateMascotChat(country);
            
            // 探検ボタンのイベントリスナーを設定
            document.getElementById('exploreBtn').onclick = function() {
                // ここで学習コンテンツページに遷移
                window.location.href = 'learningcontent.php?country=' + encodeURIComponent(country.name);
            };
        }

        // 国情報を非表示
        function hideCountryInfo() {
            document.getElementById('countryInfo').style.display = 'none';
            selectedCountry = null;
        }

        // 現在の地域を判定
        function getCurrentRegion(lat, lng) {
            for (let region of regions) {
                const bounds = region.bounds;
                if (lat >= bounds.minLat && lat <= bounds.maxLat && 
                    lng >= bounds.minLng && lng <= bounds.maxLng) {
                    return region;
                }
            }
            return null;
        }

        // 地域情報を表示
        function showRegionInfo(region) {
            document.getElementById('regionName').textContent = region.name;
            document.getElementById('regionDescription').textContent = region.description;
            document.getElementById('regionInfo').style.display = 'block';
            
            // 3秒後に自動で非表示
            setTimeout(() => {
                document.getElementById('regionInfo').style.display = 'none';
            }, 3000);
        }

        // AIアシスタントの会話
        function updateMascotChat(country) {
            const chatTitle = document.getElementById('chatTitle');
            const chatMessage = document.getElementById('chatMessage');
            
            chatTitle.textContent = `${country.flag} ${country.name}の情報`;
            chatMessage.textContent = `${country.name}についての基本情報です：${country.description} より詳細な情報が必要でしたら、下記のボタンからお選びください。`;
        }

        // AIアシスタントに質問
        function askMascot(topic) {
            if (!selectedCountry) {
                alert('まず地球儀から国を選択してください。');
                return;
            }

            const chatTitle = document.getElementById('chatTitle');
            const chatMessage = document.getElementById('chatMessage');
            
            if (topic === 'culture') {
                chatTitle.textContent = `${selectedCountry.flag} ${selectedCountry.name}の文化分析`;
                chatMessage.textContent = `${selectedCountry.name}の文化について分析いたします。伝統的なあいさつ方法、祝祭日、芸術、音楽、宗教など、多様な文化要素が存在します。特に注目すべき点として、地域特有の習慣や価値観が挙げられます。`;
            } else if (topic === 'food') {
                chatTitle.textContent = `${selectedCountry.flag} ${selectedCountry.name}の食文化`;
                chatMessage.textContent = `${selectedCountry.name}の食文化について詳しく説明いたします。伝統料理、調理法、食材、食事の習慣、食器の使い方など、食を通じて文化を理解することができます。地域によって異なる味付けや調理法が特徴的です。`;
            }
        }

        // 現在のトピックとメッセージインデックスを保持
        let currentTopic = null;
        let currentMessageIndex = 0;
        let currentMessages = [];

        // ベトナム情報を表示する関数
        function showVietnamInfo(topic) {
            // トピック選択を非表示にして会話モードに切り替え
            document.getElementById('topicSelection').classList.add('hidden');
            document.getElementById('conversationMode').classList.remove('hidden');
            
            const conversationMessages = document.getElementById('conversationMessages');
            conversationMessages.innerHTML = ''; // 会話履歴をクリア
            
            const vietnamInfo = {
                population: {
                    title: '👥 ベトナムの人口について教えるね！',
                    message: 'ベトナムには約9,700万人の人が住んでいるよ！東南アジアでも人口の多い国の一つなんだ。特に若い人が多くて、平均年齢は32歳くらいなんだよ。ホーチミン市やハノイ市にはたくさんの人が集まっているね！',
                    followUp: '人口について他に知りたいことはあるかな？例えば、都市と田舎の人口分布や、人口増加率についても教えられるよ！'
                },
                capital: {
                    title: '🏛️ ベトナムの首都はどこかな？',
                    message: 'ベトナムの首都はハノイだよ！北部にあって、1000年以上の歴史がある古い都なんだ。政治や文化の中心地で、昔の寺院と新しいビルが混ざってる、とてもきれいな街なんだよ！',
                    followUp: 'ハノイについてもっと詳しく知りたい？観光スポットや、ホーチミン市との違いについても話せるよ！'
                },
                language: {
                    title: '🗣️ ベトナムの言葉について！',
                    message: 'ベトナムの公用語はベトナム語だよ！6つの声調があるから、ちょっと難しいかもしれないね。中国語の影響も受けているんだ。でも、英語やフランス語も学校で習うから、観光地では英語が通じる場所も多いよ！',
                    followUp: 'ベトナム語を少し覚えてみたい？簡単な挨拶や、数字の数え方も教えられるよ！'
                },
                ethnicity: {
                    title: '👥 ベトナムの民族について教えるね！',
                    message: 'ベトナムには54の民族が住んでいるんだよ！一番多いのはキン族（ベトナム人）で、全体の86%くらいを占めてるんだ。他にもタイ族やムオン族、カンボジア系の人たちもいるよ！',
                    followUp: '少数民族の文化や、伝統的な祭りについても知りたい？とても興味深い話がたくさんあるよ！'
                },
                religion: {
                    title: '🙏 ベトナムの宗教について！',
                    message: 'ベトナムでは仏教が一番一般的で、約45%の人が信仰してるんだ。他にもカトリックやカオダイ教、ホアハオ教などもあるよ。面白いのは、多くの人が複数の宗教を組み合わせて信仰してることなんだ！',
                    followUp: 'ベトナムの寺院や、宗教的な祭りについても知りたい？とても美しい文化があるんだよ！'
                },
                climate: {
                    title: '🌤️ ベトナムの気候はどうかな？',
                    message: 'ベトナムは熱帯モンスーン気候なんだ！北部、中部、南部で気候が違うのが特徴だよ。北部には四季があるけど、南部は一年中暑いんだ。5月から10月は雨季で、11月から4月は乾季なんだよ！',
                    followUp: 'ベストシーズンや、観光に適した時期についても教えられるよ！いつ行くのが一番いいかな？'
                }
            };
            
            // 現在のトピックとメッセージを設定
            currentTopic = topic;
            currentMessageIndex = 0;
            currentMessages = [
                vietnamInfo[topic].title,
                vietnamInfo[topic].message,
                vietnamInfo[topic].followUp
            ];
            
            // 最初のメッセージを表示
            showCurrentMessage();
        }
        
        // 現在のメッセージを表示する関数
        function showCurrentMessage() {
            const conversationMessages = document.getElementById('conversationMessages');
            conversationMessages.innerHTML = ''; // 会話履歴をクリア
            
            if (currentMessageIndex < currentMessages.length) {
                addMessage('🇻🇳 ベトナムくん', currentMessages[currentMessageIndex], 'vietnam');
            }
        }
        
        // 次のメッセージに進む関数
        function nextMessage() {
            currentMessageIndex++;
            if (currentMessageIndex < currentMessages.length) {
                showCurrentMessage();
            } else {
                // 全てのメッセージを表示した場合
                const conversationMessages = document.getElementById('conversationMessages');
                conversationMessages.innerHTML = '';
                addMessage('🇻🇳 ベトナムくん', 'このトピックについて他に知りたいことはあるかな？', 'vietnam');
            }
        }

        // 会話メッセージを追加する関数
        function addMessage(sender, message, type = 'user') {
            const conversationMessages = document.getElementById('conversationMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${type === 'vietnam' ? 'justify-start' : 'justify-end'} mb-6`;
            
            if (type === 'vietnam') {
                // ベトナムキャラクターの吹き出し
                const characterDiv = document.createElement('div');
                characterDiv.className = 'flex items-end mr-4';
                
                const characterIcon = document.createElement('div');
                characterIcon.className = 'relative flex-shrink-0';
                characterIcon.innerHTML = `
                    <div class="text-3xl">🇻🇳</div>
                    <div class="absolute -top-1 -right-1 text-sm">🎩</div>
                `;
                
                const speechBubble = document.createElement('div');
                speechBubble.className = 'bg-blue-100 text-gray-800 px-4 py-3 rounded-2xl rounded-bl-sm max-w-xs relative ml-2';
                speechBubble.innerHTML = `
                    <div class="absolute -left-2 bottom-0 w-0 h-0 border-l-8 border-l-transparent border-r-0 border-b-8 border-b-blue-100"></div>
                    <div class="text-sm leading-relaxed">${message}</div>
                `;
                
                characterDiv.appendChild(characterIcon);
                characterDiv.appendChild(speechBubble);
                messageDiv.appendChild(characterDiv);
            } else {
                // ユーザーの吹き出し
                const speechBubble = document.createElement('div');
                speechBubble.className = 'bg-green-100 text-gray-800 px-4 py-3 rounded-2xl rounded-br-sm max-w-xs relative';
                speechBubble.innerHTML = `
                    <div class="absolute -right-2 bottom-0 w-0 h-0 border-r-8 border-r-transparent border-l-0 border-b-8 border-b-green-100"></div>
                    <div class="text-sm leading-relaxed">${message}</div>
                `;
                messageDiv.appendChild(speechBubble);
            }
            
            conversationMessages.appendChild(messageDiv);
            
            // 自動スクロール
            conversationMessages.scrollTop = conversationMessages.scrollHeight;
        }
        
        // ベトナムの朝ごはんを表示する関数
        function showVietnamBreakfast() {
            // 会話モードに切り替え
            document.getElementById('topicSelection').classList.add('hidden');
            document.getElementById('conversationMode').classList.remove('hidden');
            
            // 朝ごはんのメッセージを設定
            currentTopic = 'breakfast';
            currentMessageIndex = 0;
            currentMessages = [
                '🍜 ベトナムの朝ごはんを一緒に食べてみよう！',
                'ベトナムの朝ごはんは「フォー」が定番だよ！牛肉や鶏肉のスープにライスヌードルを入れた料理で、新鮮なハーブやライムを添えて食べるんだ。',
                '他にも「バインミー」（ベトナム風サンドイッチ）や「チャオ」（お粥）も人気なんだよ！朝からスパイシーで栄養満点の料理を楽しむのがベトナム流なんだ！',
                '一緒に食べてみない？本格的なフォーの作り方も教えられるよ！'
            ];
            
            // 最初のメッセージを表示
            showCurrentMessage();
        }
        
        // ベトナム会話をリセットする関数
        function resetVietnamChat() {
            document.getElementById('conversationMode').classList.add('hidden');
            document.getElementById('topicSelection').classList.remove('hidden');
            currentTopic = null;
            currentMessageIndex = 0;
            currentMessages = [];
        }

        // 日本からの基本情報を更新する関数
        function updateJapanInfo(country) {
            const countryInfo = {
                'ベトナム': {
                    distance: '約3,200km',
                    flightTime: '約5時間',
                    timeDiff: '-2時間',
                    currency: 'ドン（VND）',
                    temperature: '20-35°C'
                },
                '日本': {
                    distance: '0km',
                    flightTime: '-',
                    timeDiff: '±0時間',
                    currency: '円（JPY）',
                    temperature: '5-30°C'
                },
                'エジプト': {
                    distance: '約9,500km',
                    flightTime: '約12時間',
                    timeDiff: '-7時間',
                    currency: 'ポンド（EGP）',
                    temperature: '15-40°C'
                },
                'ブラジル': {
                    distance: '約18,000km',
                    flightTime: '約24時間',
                    timeDiff: '-12時間',
                    currency: 'レアル（BRL）',
                    temperature: '15-35°C'
                },
                'フランス': {
                    distance: '約9,500km',
                    flightTime: '約12時間',
                    timeDiff: '-8時間',
                    currency: 'ユーロ（EUR）',
                    temperature: '5-25°C'
                },
                'イタリア': {
                    distance: '約9,800km',
                    flightTime: '約13時間',
                    timeDiff: '-8時間',
                    currency: 'ユーロ（EUR）',
                    temperature: '10-30°C'
                },
                'インド': {
                    distance: '約6,000km',
                    flightTime: '約8時間',
                    timeDiff: '-3.5時間',
                    currency: 'ルピー（INR）',
                    temperature: '20-40°C'
                },
                '韓国': {
                    distance: '約1,000km',
                    flightTime: '約2時間',
                    timeDiff: '0時間',
                    currency: 'ウォン（KRW）',
                    temperature: '0-30°C'
                },
                'オーストラリア': {
                    distance: '約7,500km',
                    flightTime: '約9時間',
                    timeDiff: '+1時間',
                    currency: 'ドル（AUD）',
                    temperature: '10-35°C'
                }
            };

            if (countryInfo[country]) {
                document.getElementById('distance').textContent = countryInfo[country].distance;
                document.getElementById('flightTime').textContent = countryInfo[country].flightTime;
                document.getElementById('timeDiff').textContent = countryInfo[country].timeDiff;
                document.getElementById('currency').textContent = countryInfo[country].currency;
                document.getElementById('temperature').textContent = countryInfo[country].temperature;
            }
        }

        // ページ読み込み完了後に地球儀を初期化
        window.addEventListener('load', function() {
            setTimeout(initGlobe, 100);
        });
    </script>

    <!-- フッター -->
    <footer class="bg-gray-200 border-t border-gray-300">
        <div class="w-full max-w-6xl mx-auto px-4 py-2 flex items-center justify-center">
            <div class="text-center text-gray-600">
                <p>&copy; 2025 世界の小さな日常</p>
            </div>
        </div>
    </footer>
</body>
</html> 