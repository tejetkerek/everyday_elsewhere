<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>国を選ぶ - 世界の小さな日常パスポート</title>
    <script src="https://unpkg.com/three@0.150.1/build/three.min.js"></script>
    <script src="https://unpkg.com/globe.gl@2.32.0/dist/globe.gl.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { margin: 0; }
        #globeViz { width: 100%; height: 100vh; }
        .country-info {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            z-index: 1000;
        }
        .navigation {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .country-card {
            background: white;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .country-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #333;
            font-size: 18px;
            z-index: 1000;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="loading" id="loading">地球儀を読み込み中...</div>
    
    <!-- ナビゲーション -->
    <div class="navigation">
        <a href="dashboard.php" class="bg-white hover:bg-gray-100 text-gray-800 font-bold py-3 px-6 rounded-xl shadow-lg transition-colors inline-flex items-center">
            <i class="fas fa-home mr-2"></i>
            ダッシュボード
        </a>
    </div>

    <!-- 国情報パネル -->
    <div class="country-info" id="countryInfo" style="display: none;">
        <h3 class="text-lg font-bold text-gray-800 mb-2" id="countryName">国名</h3>
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

    <div id="globeViz"></div>

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
                color: '#ff4444'
            },
            {
                name: 'エジプト',
                name_en: 'Egypt',
                flag: '🇪🇬',
                lat: 30.0444,
                lng: 31.2357,
                description: 'ピラミッドとスフィンクス、ナイル川。古代文明の神秘的な国。',
                color: '#ffaa00'
            },
            {
                name: 'ブラジル',
                name_en: 'Brazil',
                flag: '🇧🇷',
                lat: -15.7801,
                lng: -47.9292,
                description: 'サンバとサッカー、アマゾンの熱帯雨林。情熱的な南米の国。',
                color: '#00aa44'
            },
            {
                name: 'フランス',
                name_en: 'France',
                flag: '🇫🇷',
                lat: 48.8566,
                lng: 2.3522,
                description: 'エッフェル塔とルーブル美術館、クロワッサンとワインの国。',
                color: '#4444ff'
            },
            {
                name: 'イタリア',
                name_en: 'Italy',
                flag: '🇮🇹',
                lat: 41.9028,
                lng: 12.4964,
                description: 'ピザとパスタ、コロッセオとヴェネツィア。芸術と美食の国。',
                color: '#44aa44'
            },
            {
                name: 'インド',
                name_en: 'India',
                flag: '🇮🇳',
                lat: 28.6139,
                lng: 77.2090,
                description: 'カレーとヨガ、タージマハルとガンジス川。多様な文化の国。',
                color: '#ff8800'
            },
            {
                name: '韓国',
                name_en: 'South Korea',
                flag: '🇰🇷',
                lat: 37.5665,
                lng: 126.9780,
                description: 'キムチとK-POP、韓国料理と伝統文化。現代と伝統が融合する国。',
                color: '#4444aa'
            },
            {
                name: 'オーストラリア',
                name_en: 'Australia',
                flag: '🇦🇺',
                lat: -33.8688,
                lng: 151.2093,
                description: 'コアラとカンガルー、グレートバリアリーフ。自然豊かな大陸。',
                color: '#aa4444'
            }
        ];

        let selectedCountry = null;
        let globe = null;

        // エラーハンドリング
        window.addEventListener('error', function(e) {
            console.error('JavaScript error:', e.error);
            document.getElementById('loading').innerHTML = 'エラーが発生しました。ページを再読み込みしてください。';
        });

        // 地球儀の初期化
        function initGlobe() {
            try {
                globe = Globe()
                    (document.getElementById('globeViz'))
                    .globeImageUrl('//unpkg.com/three-globe/example/img/earth-blue-marble.jpg')
                    .backgroundColor('#f0f4f8')
                    .showAtmosphere(true)
                    .atmosphereColor('#3a7ca5')
                    .atmosphereAltitude(0.15)
                    .width(window.innerWidth)
                    .height(window.innerHeight);

                // ポイントデータを設定
                globe
                    .pointsData(countries)
                    .pointLat('lat')
                    .pointLng('lng')
                    .pointColor('color')
                    .pointAltitude(0.01)
                    .pointRadius(0.8)
                    .pointLabel('name')
                    .onPointClick(handleCountryClick);

                // 読み込み完了
                document.getElementById('loading').style.display = 'none';

                // ウィンドウリサイズ対応
                window.addEventListener('resize', () => {
                    globe.width(window.innerWidth).height(window.innerHeight);
                });

            } catch (error) {
                console.error('Globe initialization error:', error);
                document.getElementById('loading').innerHTML = '地球儀の初期化に失敗しました。';
            }
        }

        // 国クリック時の処理
        function handleCountryClick(country) {
            selectedCountry = country;
            showCountryInfo(country);
        }

        // 国情報を表示
        function showCountryInfo(country) {
            const countryInfo = document.getElementById('countryInfo');
            const countryName = document.getElementById('countryName');
            const countryDescription = document.getElementById('countryDescription');
            const exploreBtn = document.getElementById('exploreBtn');

            countryName.innerHTML = `${country.flag} ${country.name} (${country.name_en})`;
            countryDescription.textContent = country.description;
            
            // 探検ボタンのリンクを設定
            exploreBtn.onclick = function() {
                window.location.href = `learningcontent.php?country=${country.name_en.toLowerCase()}`;
            };

            countryInfo.style.display = 'block';
        }

        // 国情報を隠す
        function hideCountryInfo() {
            document.getElementById('countryInfo').style.display = 'none';
            selectedCountry = null;
        }

        // DOM読み込み完了後に初期化
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initGlobe);
        } else {
            initGlobe();
        }

        // キーボードショートカット
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideCountryInfo();
            }
        });
    </script>
</body>
</html>
