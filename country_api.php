<?php
/**
 * 各国の情報を取得するためのAPI関数
 */

// 国情報を取得する関数
function getCountryInfo($countryName) {
    $apiUrl = "https://restcountries.com/v3.1/name/" . urlencode($countryName);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 && $response) {
        $data = json_decode($response, true);
        if ($data && isset($data[0])) {
            return formatCountryData($data[0]);
        }
    }
    
    return null;
}

// すべての国を取得
function getAllCountries() {
    $apiUrl = "https://countriesnow.space/api/v0.1/countries";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // 30秒から10秒に短縮
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 && $response) {
        $data = json_decode($response, true);
        if ($data && isset($data['data'])) {
            $formattedData = [];
            foreach ($data['data'] as $country) {
                $formattedData[] = formatCountryDataNew($country);
            }
            return $formattedData;
        }
    }
    
    // API接続が失敗した場合のフォールバックデータ
    return getFallbackCountries();
}

// フォールバック用の国データ
function getFallbackCountries() {
    return [
        [
            'name' => 'Japan',
            'name_jp' => '日本',
            'flag' => '🇯🇵',
            'capital' => 'Tokyo',
            'population' => '125,700,000',
            'region' => 'Asia',
            'subregion' => 'Eastern Asia',
            'languages' => ['Japanese'],
            'currencies' => ['JPY'],
            'timezones' => ['UTC+09:00'],
            'area' => 377975,
            'borders' => [],
            'flag_url' => '',
            'coat_of_arms' => ''
        ],
        [
            'name' => 'Vietnam',
            'name_jp' => 'ベトナム',
            'flag' => '🇻🇳',
            'capital' => 'Hanoi',
            'population' => '97,338,579',
            'region' => 'Asia',
            'subregion' => 'South-Eastern Asia',
            'languages' => ['Vietnamese'],
            'currencies' => ['VND'],
            'timezones' => ['UTC+07:00'],
            'area' => 331212,
            'borders' => ['Cambodia', 'China', 'Laos'],
            'flag_url' => '',
            'coat_of_arms' => ''
        ],
        [
            'name' => 'France',
            'name_jp' => 'フランス',
            'flag' => '🇫🇷',
            'capital' => 'Paris',
            'population' => '67,391,582',
            'region' => 'Europe',
            'subregion' => 'Western Europe',
            'languages' => ['French'],
            'currencies' => ['EUR'],
            'timezones' => ['UTC+01:00'],
            'area' => 551695,
            'borders' => ['Andorra', 'Belgium', 'Germany', 'Italy', 'Luxembourg', 'Monaco', 'Spain', 'Switzerland'],
            'flag_url' => '',
            'coat_of_arms' => ''
        ],
        [
            'name' => 'Italy',
            'name_jp' => 'イタリア',
            'flag' => '🇮🇹',
            'capital' => 'Rome',
            'population' => '60,461,826',
            'region' => 'Europe',
            'subregion' => 'Southern Europe',
            'languages' => ['Italian'],
            'currencies' => ['EUR'],
            'timezones' => ['UTC+01:00'],
            'area' => 301336,
            'borders' => ['Austria', 'France', 'San Marino', 'Slovenia', 'Switzerland', 'Vatican City'],
            'flag_url' => '',
            'coat_of_arms' => ''
        ],
        [
            'name' => 'Brazil',
            'name_jp' => 'ブラジル',
            'flag' => '🇧🇷',
            'capital' => 'Brasília',
            'population' => '212,559,417',
            'region' => 'Americas',
            'subregion' => 'South America',
            'languages' => ['Portuguese'],
            'currencies' => ['BRL'],
            'timezones' => ['UTC-05:00', 'UTC-04:00', 'UTC-03:00', 'UTC-02:00'],
            'area' => 8515767,
            'borders' => ['Argentina', 'Bolivia', 'Colombia', 'French Guiana', 'Guyana', 'Paraguay', 'Peru', 'Suriname', 'Uruguay', 'Venezuela'],
            'flag_url' => '',
            'coat_of_arms' => ''
        ],
        [
            'name' => 'Egypt',
            'name_jp' => 'エジプト',
            'flag' => '🇪🇬',
            'capital' => 'Cairo',
            'population' => '102,334,404',
            'region' => 'Africa',
            'subregion' => 'Northern Africa',
            'languages' => ['Arabic'],
            'currencies' => ['EGP'],
            'timezones' => ['UTC+02:00'],
            'area' => 1002450,
            'borders' => ['Gaza Strip', 'Israel', 'Libya', 'Sudan'],
            'flag_url' => '',
            'coat_of_arms' => ''
        ],
        [
            'name' => 'India',
            'name_jp' => 'インド',
            'flag' => '🇮🇳',
            'capital' => 'New Delhi',
            'population' => '1,380,004,385',
            'region' => 'Asia',
            'subregion' => 'Southern Asia',
            'languages' => ['Hindi', 'English'],
            'currencies' => ['INR'],
            'timezones' => ['UTC+05:30'],
            'area' => 3287590,
            'borders' => ['Afghanistan', 'Bangladesh', 'Bhutan', 'China', 'Myanmar', 'Nepal', 'Pakistan'],
            'flag_url' => '',
            'coat_of_arms' => ''
        ],
        [
            'name' => 'South Korea',
            'name_jp' => '韓国',
            'flag' => '🇰🇷',
            'capital' => 'Seoul',
            'population' => '51,269,185',
            'region' => 'Asia',
            'subregion' => 'Eastern Asia',
            'languages' => ['Korean'],
            'currencies' => ['KRW'],
            'timezones' => ['UTC+09:00'],
            'area' => 100210,
            'borders' => ['North Korea'],
            'flag_url' => '',
            'coat_of_arms' => ''
        ]
    ];
}

// 国データをフォーマットする関数
function formatCountryData($countryData) {
    return [
        'name' => $countryData['name']['common'] ?? 'Unknown',
        'name_jp' => $countryData['translations']['jpn']['common'] ?? $countryData['name']['common'],
        'flag' => $countryData['flags']['emoji'] ?? '🏳️',
        'capital' => $countryData['capital'][0] ?? 'Unknown',
        'population' => number_format($countryData['population'] ?? 0),
        'region' => $countryData['region'] ?? 'Unknown',
        'subregion' => $countryData['subregion'] ?? '',
        'languages' => $countryData['languages'] ?? [],
        'currencies' => $countryData['currencies'] ?? [],
        'timezones' => $countryData['timezones'] ?? [],
        'area' => $countryData['area'] ?? 0,
        'borders' => $countryData['borders'] ?? [],
        'flag_url' => $countryData['flags']['png'] ?? '',
        'coat_of_arms' => $countryData['coatOfArms']['png'] ?? ''
    ];
}

// v2用のデータフォーマット関数
function formatCountryDataV2($countryData) {
    return [
        'name' => $countryData['name'] ?? 'Unknown',
        'name_jp' => $countryData['translations']['ja'] ?? $countryData['name'] ?? 'Unknown',
        'flag' => $countryData['flag'] ?? '🏳️',
        'capital' => $countryData['capital'] ?? 'Unknown',
        'population' => number_format($countryData['population'] ?? 0),
        'region' => $countryData['region'] ?? 'Unknown',
        'subregion' => $countryData['subregion'] ?? '',
        'languages' => $countryData['languages'] ?? [],
        'currencies' => $countryData['currencies'] ?? [],
        'timezones' => $countryData['timezones'] ?? [],
        'area' => $countryData['area'] ?? 0,
        'borders' => $countryData['borders'] ?? [],
        'flag_url' => $countryData['flag'] ?? '',
        'coat_of_arms' => ''
    ];
}

// 新しいAPI用のデータフォーマット関数
function formatCountryDataNew($countryData) {
    $countryName = $countryData['country'] ?? 'Unknown';
    return [
        'name' => $countryName,
        'name_jp' => getJapaneseName($countryName),
        'flag' => getCountryFlag($countryName),
        'flag_image' => getCountryFlagImage($countryName),
        'capital' => $countryData['cities'][0] ?? 'Unknown',
        'population' => number_format(rand(1000000, 100000000)), // 仮の人口
        'region' => getRegionFromCountry($countryName),
        'subregion' => '',
        'languages' => [],
        'currencies' => [],
        'timezones' => [],
        'area' => rand(10000, 10000000), // 仮の面積
        'borders' => [],
        'flag_url' => '',
        'coat_of_arms' => ''
    ];
}

// 国旗画像を取得する関数
function getCountryFlagImage($countryName) {
    // 国名から国コードを取得
    $countryCodes = [
        'Afghanistan' => 'af', 'Albania' => 'al', 'Algeria' => 'dz', 'Andorra' => 'ad', 'Angola' => 'ao',
        'Antigua and Barbuda' => 'ag', 'Argentina' => 'ar', 'Armenia' => 'am', 'Australia' => 'au', 'Austria' => 'at',
        'Azerbaijan' => 'az', 'Bahamas' => 'bs', 'Bahrain' => 'bh', 'Bangladesh' => 'bd', 'Barbados' => 'bb',
        'Belarus' => 'by', 'Belgium' => 'be', 'Belize' => 'bz', 'Benin' => 'bj', 'Bhutan' => 'bt',
        'Bolivia' => 'bo', 'Bosnia and Herzegovina' => 'ba', 'Botswana' => 'bw', 'Brazil' => 'br', 'Brunei' => 'bn',
        'Bulgaria' => 'bg', 'Burkina Faso' => 'bf', 'Burundi' => 'bi', 'Cambodia' => 'kh', 'Cameroon' => 'cm',
        'Canada' => 'ca', 'Cape Verde' => 'cv', 'Central African Republic' => 'cf', 'Chad' => 'td', 'Chile' => 'cl',
        'China' => 'cn', 'Colombia' => 'co', 'Comoros' => 'km', 'Costa Rica' => 'cr', 'Croatia' => 'hr',
        'Cuba' => 'cu', 'Cyprus' => 'cy', 'Czech Republic' => 'cz', 'Democratic Republic of the Congo' => 'cd',
        'Denmark' => 'dk', 'Djibouti' => 'dj', 'Dominica' => 'dm', 'Dominican Republic' => 'do', 'Ecuador' => 'ec',
        'Egypt' => 'eg', 'El Salvador' => 'sv', 'Equatorial Guinea' => 'gq', 'Eritrea' => 'er', 'Estonia' => 'ee',
        'Eswatini' => 'sz', 'Ethiopia' => 'et', 'Fiji' => 'fj', 'Finland' => 'fi', 'France' => 'fr',
        'Gabon' => 'ga', 'Gambia' => 'gm', 'Georgia' => 'ge', 'Germany' => 'de', 'Ghana' => 'gh',
        'Greece' => 'gr', 'Grenada' => 'gd', 'Guatemala' => 'gt', 'Guinea' => 'gn', 'Guinea-Bissau' => 'gw',
        'Guyana' => 'gy', 'Haiti' => 'ht', 'Honduras' => 'hn', 'Hungary' => 'hu', 'Iceland' => 'is',
        'India' => 'in', 'Indonesia' => 'id', 'Iran' => 'ir', 'Iraq' => 'iq', 'Ireland' => 'ie',
        'Israel' => 'il', 'Italy' => 'it', 'Ivory Coast' => 'ci', 'Jamaica' => 'jm', 'Japan' => 'jp',
        'Jordan' => 'jo', 'Kazakhstan' => 'kz', 'Kenya' => 'ke', 'Kiribati' => 'ki', 'Kuwait' => 'kw',
        'Kyrgyzstan' => 'kg', 'Laos' => 'la', 'Latvia' => 'lv', 'Lebanon' => 'lb', 'Lesotho' => 'ls',
        'Liberia' => 'lr', 'Libya' => 'ly', 'Lithuania' => 'lt', 'Luxembourg' => 'lu', 'Madagascar' => 'mg',
        'Malawi' => 'mw', 'Malaysia' => 'my', 'Maldives' => 'mv', 'Mali' => 'ml', 'Malta' => 'mt',
        'Marshall Islands' => 'mh', 'Mauritania' => 'mr', 'Mauritius' => 'mu', 'Mexico' => 'mx', 'Micronesia' => 'fm',
        'Moldova' => 'md', 'Monaco' => 'mc', 'Mongolia' => 'mn', 'Montenegro' => 'me', 'Morocco' => 'ma',
        'Mozambique' => 'mz', 'Myanmar' => 'mm', 'Namibia' => 'na', 'Nauru' => 'nr', 'Nepal' => 'np',
        'Netherlands' => 'nl', 'New Zealand' => 'nz', 'Nicaragua' => 'ni', 'Niger' => 'ne', 'Nigeria' => 'ng',
        'North Korea' => 'kp', 'North Macedonia' => 'mk', 'Norway' => 'no', 'Oman' => 'om', 'Pakistan' => 'pk',
        'Palau' => 'pw', 'Panama' => 'pa', 'Papua New Guinea' => 'pg', 'Paraguay' => 'py', 'Peru' => 'pe',
        'Philippines' => 'ph', 'Poland' => 'pl', 'Portugal' => 'pt', 'Qatar' => 'qa', 'Romania' => 'ro',
        'Russia' => 'ru', 'Rwanda' => 'rw', 'Saint Kitts and Nevis' => 'kn', 'Saint Lucia' => 'lc',
        'Saint Vincent and the Grenadines' => 'vc', 'Samoa' => 'ws', 'San Marino' => 'sm', 'Sao Tome and Principe' => 'st',
        'Saudi Arabia' => 'sa', 'Senegal' => 'sn', 'Serbia' => 'rs', 'Seychelles' => 'sc', 'Sierra Leone' => 'sl',
        'Singapore' => 'sg', 'Slovakia' => 'sk', 'Slovenia' => 'si', 'Solomon Islands' => 'sb', 'Somalia' => 'so',
        'South Africa' => 'za', 'South Korea' => 'kr', 'South Sudan' => 'ss', 'Spain' => 'es', 'Sri Lanka' => 'lk',
        'Sudan' => 'sd', 'Suriname' => 'sr', 'Sweden' => 'se', 'Switzerland' => 'ch', 'Syria' => 'sy',
        'Taiwan' => 'tw', 'Tajikistan' => 'tj', 'Tanzania' => 'tz', 'Thailand' => 'th', 'Togo' => 'tg',
        'Tonga' => 'to', 'Trinidad and Tobago' => 'tt', 'Tunisia' => 'tn', 'Turkey' => 'tr', 'Turkmenistan' => 'tm',
        'Tuvalu' => 'tv', 'Uganda' => 'ug', 'Ukraine' => 'ua', 'United Arab Emirates' => 'ae', 'United Kingdom' => 'gb',
        'United States' => 'us', 'Uruguay' => 'uy', 'Uzbekistan' => 'uz', 'Vanuatu' => 'vu', 'Vatican City' => 'va',
        'Venezuela' => 've', 'Vietnam' => 'vn', 'Yemen' => 'ye', 'Zambia' => 'zm', 'Zimbabwe' => 'zw'
    ];
    
    $countryCode = $countryCodes[$countryName] ?? null;
    
    if ($countryCode) {
        return "https://flagcdn.com/w320/{$countryCode}.png";
    }
    
    return null;
}

// 国名から地域を判定（改善版）
function getRegionFromCountry($countryName) {
    $asiaCountries = ['Japan', 'China', 'India', 'Vietnam', 'Thailand', 'South Korea', 'North Korea', 'Mongolia', 'Nepal', 'Bhutan', 'Bangladesh', 'Sri Lanka', 'Myanmar', 'Laos', 'Cambodia', 'Malaysia', 'Singapore', 'Indonesia', 'Philippines', 'Taiwan', 'Hong Kong', 'Macau', 'Afghanistan', 'Pakistan', 'Iran', 'Iraq', 'Syria', 'Lebanon', 'Jordan', 'Israel', 'Palestine', 'Saudi Arabia', 'Yemen', 'Oman', 'UAE', 'Qatar', 'Kuwait', 'Bahrain', 'Kazakhstan', 'Uzbekistan', 'Turkmenistan', 'Kyrgyzstan', 'Tajikistan', 'Azerbaijan', 'Georgia', 'Armenia', 'Turkey', 'Cyprus'];
    $europeCountries = ['France', 'Germany', 'Italy', 'Spain', 'United Kingdom', 'Netherlands', 'Belgium', 'Switzerland', 'Austria', 'Poland', 'Czech Republic', 'Slovakia', 'Hungary', 'Romania', 'Bulgaria', 'Greece', 'Portugal', 'Ireland', 'Denmark', 'Sweden', 'Norway', 'Finland', 'Iceland', 'Albania', 'Andorra', 'Bosnia and Herzegovina', 'Croatia', 'Estonia', 'Latvia', 'Lithuania', 'Luxembourg', 'Malta', 'Moldova', 'Monaco', 'Montenegro', 'North Macedonia', 'San Marino', 'Serbia', 'Slovenia', 'Vatican City', 'Belarus', 'Ukraine', 'Russia'];
    $africaCountries = ['Egypt', 'South Africa', 'Nigeria', 'Kenya', 'Ethiopia', 'Morocco', 'Algeria', 'Tunisia', 'Libya', 'Sudan', 'Chad', 'Niger', 'Mali', 'Senegal', 'Ghana', 'Ivory Coast', 'Cameroon', 'Central African Republic', 'Democratic Republic of the Congo', 'Angola', 'Zambia', 'Zimbabwe', 'Botswana', 'Namibia', 'Algeria', 'Benin', 'Burkina Faso', 'Burundi', 'Cape Verde', 'Comoros', 'Djibouti', 'Equatorial Guinea', 'Eritrea', 'Gabon', 'Guinea', 'Guinea-Bissau', 'Lesotho', 'Liberia', 'Madagascar', 'Malawi', 'Mauritania', 'Mauritius', 'Mozambique', 'Rwanda', 'Sao Tome and Principe', 'Seychelles', 'Sierra Leone', 'Somalia', 'South Sudan', 'Tanzania', 'Togo', 'Uganda'];
    $americasCountries = ['United States', 'Canada', 'Mexico', 'Brazil', 'Argentina', 'Chile', 'Peru', 'Colombia', 'Venezuela', 'Ecuador', 'Bolivia', 'Paraguay', 'Uruguay', 'Guyana', 'Suriname', 'French Guiana', 'Antigua and Barbuda', 'Bahamas', 'Barbados', 'Belize', 'Costa Rica', 'Cuba', 'Dominica', 'Dominican Republic', 'El Salvador', 'Grenada', 'Guatemala', 'Haiti', 'Honduras', 'Jamaica', 'Nicaragua', 'Panama', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Trinidad and Tobago'];
    $oceaniaCountries = ['Australia', 'New Zealand', 'Papua New Guinea', 'Fiji', 'Solomon Islands', 'Vanuatu', 'New Caledonia', 'French Polynesia', 'Kiribati', 'Marshall Islands', 'Micronesia', 'Nauru', 'Palau', 'Samoa', 'Tonga', 'Tuvalu'];
    
    if (in_array($countryName, $asiaCountries)) return 'Asia';
    if (in_array($countryName, $europeCountries)) return 'Europe';
    if (in_array($countryName, $africaCountries)) return 'Africa';
    if (in_array($countryName, $americasCountries)) return 'Americas';
    if (in_array($countryName, $oceaniaCountries)) return 'Oceania';
    
    return 'Unknown';
}

// 国名から国旗絵文字を取得（拡張版）
function getCountryFlag($countryName) {
    $flags = [
        // アジア
        'Japan' => '🇯🇵', 'China' => '🇨🇳', 'India' => '🇮🇳', 'Vietnam' => '🇻🇳', 'Thailand' => '🇹🇭',
        'South Korea' => '🇰🇷', 'North Korea' => '🇰🇵', 'Mongolia' => '🇲🇳', 'Nepal' => '🇳🇵', 'Bhutan' => '🇧🇹',
        'Bangladesh' => '🇧🇩', 'Sri Lanka' => '🇱🇰', 'Myanmar' => '🇲🇲', 'Laos' => '🇱🇦', 'Cambodia' => '🇰🇭',
        'Malaysia' => '🇲🇾', 'Singapore' => '🇸🇬', 'Indonesia' => '🇮🇩', 'Philippines' => '🇵🇭', 'Taiwan' => '🇹🇼',
        'Afghanistan' => '🇦🇫', 'Pakistan' => '🇵🇰', 'Iran' => '🇮🇷', 'Iraq' => '🇮🇶', 'Syria' => '🇸🇾',
        'Lebanon' => '🇱🇧', 'Jordan' => '🇯🇴', 'Israel' => '🇮🇱', 'Palestine' => '🇵🇸', 'Saudi Arabia' => '🇸🇦',
        'Yemen' => '🇾🇪', 'Oman' => '🇴🇲', 'UAE' => '🇦🇪', 'Qatar' => '🇶🇦', 'Kuwait' => '🇰🇼',
        'Bahrain' => '🇧🇭', 'Kazakhstan' => '🇰🇿', 'Uzbekistan' => '🇺🇿', 'Turkmenistan' => '🇹🇲',
        'Kyrgyzstan' => '🇰🇬', 'Tajikistan' => '🇹🇯', 'Azerbaijan' => '🇦🇿', 'Georgia' => '🇬🇪',
        'Armenia' => '🇦🇲', 'Turkey' => '🇹🇷', 'Cyprus' => '🇨🇾',
        
        // ヨーロッパ
        'France' => '🇫🇷', 'Germany' => '🇩🇪', 'Italy' => '🇮🇹', 'Spain' => '🇪🇸', 'United Kingdom' => '🇬🇧',
        'Netherlands' => '🇳🇱', 'Belgium' => '🇧🇪', 'Switzerland' => '🇨🇭', 'Austria' => '🇦🇹', 'Poland' => '🇵🇱',
        'Czech Republic' => '🇨🇿', 'Slovakia' => '🇸🇰', 'Hungary' => '🇭🇺', 'Romania' => '🇷🇴', 'Bulgaria' => '🇧🇬',
        'Greece' => '🇬🇷', 'Portugal' => '🇵🇹', 'Ireland' => '🇮🇪', 'Denmark' => '🇩🇰', 'Sweden' => '🇸🇪',
        'Norway' => '🇳🇴', 'Finland' => '🇫🇮', 'Iceland' => '🇮🇸', 'Albania' => '🇦🇱', 'Andorra' => '🇦🇩',
        'Bosnia and Herzegovina' => '🇧🇦', 'Croatia' => '🇭🇷', 'Estonia' => '🇪🇪', 'Latvia' => '🇱🇻',
        'Lithuania' => '🇱🇹', 'Luxembourg' => '🇱🇺', 'Malta' => '🇲🇹', 'Moldova' => '🇲🇩', 'Monaco' => '🇲🇨',
        'Montenegro' => '🇲🇪', 'North Macedonia' => '🇲🇰', 'San Marino' => '🇸🇲', 'Serbia' => '🇷🇸',
        'Slovenia' => '🇸🇮', 'Vatican City' => '🇻🇦', 'Belarus' => '🇧🇾', 'Ukraine' => '🇺🇦', 'Russia' => '🇷🇺',
        
        // アフリカ
        'Egypt' => '🇪🇬', 'South Africa' => '🇿🇦', 'Nigeria' => '🇳🇬', 'Kenya' => '🇰🇪', 'Ethiopia' => '🇪🇹',
        'Morocco' => '🇲🇦', 'Algeria' => '🇩🇿', 'Tunisia' => '🇹🇳', 'Libya' => '🇱🇾', 'Sudan' => '🇸🇩',
        'Chad' => '🇹🇩', 'Niger' => '🇳🇪', 'Mali' => '🇲🇱', 'Senegal' => '🇸🇳', 'Ghana' => '🇬🇭',
        'Ivory Coast' => '🇨🇮', 'Cameroon' => '🇨🇲', 'Central African Republic' => '🇨🇫', 'Democratic Republic of the Congo' => '🇨🇩',
        'Angola' => '🇦🇴', 'Zambia' => '🇿🇲', 'Zimbabwe' => '🇿🇼', 'Botswana' => '🇧🇼', 'Namibia' => '🇳🇦',
        'Benin' => '🇧🇯', 'Burkina Faso' => '🇧🇫', 'Burundi' => '🇧🇮', 'Cape Verde' => '🇨🇻', 'Comoros' => '🇰🇲',
        'Djibouti' => '🇩🇯', 'Equatorial Guinea' => '🇬🇼', 'Eritrea' => '🇪🇷', 'Gabon' => '🇬🇦', 'Guinea' => '🇬🇳',
        'Guinea-Bissau' => '🇬🇼', 'Lesotho' => '🇱🇸', 'Liberia' => '🇱🇷', 'Madagascar' => '🇲🇬', 'Malawi' => '🇲🇼',
        'Mauritania' => '🇲🇷', 'Mauritius' => '🇲🇺', 'Mozambique' => '🇲🇿', 'Rwanda' => '🇷🇼', 'Sao Tome and Principe' => '🇸🇹',
        'Seychelles' => '🇸🇨', 'Sierra Leone' => '🇸🇱', 'Somalia' => '🇸🇴', 'South Sudan' => '🇸🇸', 'Tanzania' => '🇹🇿',
        'Togo' => '🇹🇬', 'Uganda' => '🇺🇬',
        
        // アメリカ
        'United States' => '🇺🇸', 'Canada' => '🇨🇦', 'Mexico' => '🇲🇽', 'Brazil' => '🇧🇷', 'Argentina' => '🇦🇷',
        'Chile' => '🇨🇱', 'Peru' => '🇵🇪', 'Colombia' => '🇨🇴', 'Venezuela' => '🇻🇪', 'Ecuador' => '🇪🇨',
        'Bolivia' => '🇧🇴', 'Paraguay' => '🇵🇾', 'Uruguay' => '🇺🇾', 'Guyana' => '🇬🇾', 'Suriname' => '🇸🇷',
        'Antigua and Barbuda' => '🇦🇬', 'Bahamas' => '🇧🇸', 'Barbados' => '🇧🇧', 'Belize' => '🇧🇿', 'Costa Rica' => '🇨🇷',
        'Cuba' => '🇨🇺', 'Dominica' => '🇩🇲', 'Dominican Republic' => '🇩🇴', 'El Salvador' => '🇸🇻', 'Grenada' => '🇬🇩',
        'Guatemala' => '🇬🇹', 'Haiti' => '🇭🇹', 'Honduras' => '🇭🇳', 'Jamaica' => '🇯🇲', 'Nicaragua' => '🇳🇮',
        'Panama' => '🇵🇦', 'Saint Kitts and Nevis' => '🇰🇳', 'Saint Lucia' => '🇱🇨', 'Saint Vincent and the Grenadines' => '🇻🇨',
        'Trinidad and Tobago' => '🇹🇹',
        
        // オセアニア
        'Australia' => '🇦🇺', 'New Zealand' => '🇳🇿', 'Papua New Guinea' => '🇵🇬', 'Fiji' => '🇫🇯',
        'Solomon Islands' => '🇸🇧', 'Vanuatu' => '🇻🇺', 'Kiribati' => '🇰🇮', 'Marshall Islands' => '🇲🇭',
        'Micronesia' => '🇫🇲', 'Nauru' => '🇳🇷', 'Palau' => '🇵🇼', 'Samoa' => '🇼🇸', 'Tonga' => '🇹🇴', 'Tuvalu' => '🇹🇻'
    ];
    
    return $flags[$countryName] ?? '🏳️';
}

// 国名の日本語表記を取得
function getJapaneseName($countryName) {
    $japaneseNames = [
        // アジア
        'Japan' => '日本', 'China' => '中国', 'India' => 'インド', 'Vietnam' => 'ベトナム', 'Thailand' => 'タイ',
        'South Korea' => '韓国', 'North Korea' => '北朝鮮', 'Mongolia' => 'モンゴル', 'Nepal' => 'ネパール', 'Bhutan' => 'ブータン',
        'Bangladesh' => 'バングラデシュ', 'Sri Lanka' => 'スリランカ', 'Myanmar' => 'ミャンマー', 'Laos' => 'ラオス', 'Cambodia' => 'カンボジア',
        'Malaysia' => 'マレーシア', 'Singapore' => 'シンガポール', 'Indonesia' => 'インドネシア', 'Philippines' => 'フィリピン', 'Taiwan' => '台湾',
        'Afghanistan' => 'アフガニスタン', 'Pakistan' => 'パキスタン', 'Iran' => 'イラン', 'Iraq' => 'イラク', 'Syria' => 'シリア',
        'Lebanon' => 'レバノン', 'Jordan' => 'ヨルダン', 'Israel' => 'イスラエル', 'Palestine' => 'パレスチナ', 'Saudi Arabia' => 'サウジアラビア',
        'Yemen' => 'イエメン', 'Oman' => 'オマーン', 'UAE' => 'アラブ首長国連邦', 'Qatar' => 'カタール', 'Kuwait' => 'クウェート',
        'Bahrain' => 'バーレーン', 'Kazakhstan' => 'カザフスタン', 'Uzbekistan' => 'ウズベキスタン', 'Turkmenistan' => 'トルクメニスタン',
        'Kyrgyzstan' => 'キルギス', 'Tajikistan' => 'タジキスタン', 'Azerbaijan' => 'アゼルバイジャン', 'Georgia' => 'ジョージア',
        'Armenia' => 'アルメニア', 'Turkey' => 'トルコ', 'Cyprus' => 'キプロス',
        
        // ヨーロッパ
        'France' => 'フランス', 'Germany' => 'ドイツ', 'Italy' => 'イタリア', 'Spain' => 'スペイン', 'United Kingdom' => 'イギリス',
        'Netherlands' => 'オランダ', 'Belgium' => 'ベルギー', 'Switzerland' => 'スイス', 'Austria' => 'オーストリア', 'Poland' => 'ポーランド',
        'Czech Republic' => 'チェコ', 'Slovakia' => 'スロバキア', 'Hungary' => 'ハンガリー', 'Romania' => 'ルーマニア', 'Bulgaria' => 'ブルガリア',
        'Greece' => 'ギリシャ', 'Portugal' => 'ポルトガル', 'Ireland' => 'アイルランド', 'Denmark' => 'デンマーク', 'Sweden' => 'スウェーデン',
        'Norway' => 'ノルウェー', 'Finland' => 'フィンランド', 'Iceland' => 'アイスランド', 'Albania' => 'アルバニア', 'Andorra' => 'アンドラ',
        'Bosnia and Herzegovina' => 'ボスニア・ヘルツェゴビナ', 'Croatia' => 'クロアチア', 'Estonia' => 'エストニア', 'Latvia' => 'ラトビア',
        'Lithuania' => 'リトアニア', 'Luxembourg' => 'ルクセンブルク', 'Malta' => 'マルタ', 'Moldova' => 'モルドバ', 'Monaco' => 'モナコ',
        'Montenegro' => 'モンテネグロ', 'North Macedonia' => '北マケドニア', 'San Marino' => 'サンマリノ', 'Serbia' => 'セルビア',
        'Slovenia' => 'スロベニア', 'Vatican City' => 'バチカン', 'Belarus' => 'ベラルーシ', 'Ukraine' => 'ウクライナ', 'Russia' => 'ロシア',
        
        // アフリカ
        'Egypt' => 'エジプト', 'South Africa' => '南アフリカ', 'Nigeria' => 'ナイジェリア', 'Kenya' => 'ケニア', 'Ethiopia' => 'エチオピア',
        'Morocco' => 'モロッコ', 'Algeria' => 'アルジェリア', 'Tunisia' => 'チュニジア', 'Libya' => 'リビア', 'Sudan' => 'スーダン',
        'Chad' => 'チャド', 'Niger' => 'ニジェール', 'Mali' => 'マリ', 'Senegal' => 'セネガル', 'Ghana' => 'ガーナ',
        'Ivory Coast' => 'コートジボワール', 'Cameroon' => 'カメルーン', 'Central African Republic' => '中央アフリカ共和国', 'Democratic Republic of the Congo' => 'コンゴ民主共和国',
        'Angola' => 'アンゴラ', 'Zambia' => 'ザンビア', 'Zimbabwe' => 'ジンバブエ', 'Botswana' => 'ボツワナ', 'Namibia' => 'ナミビア',
        'Benin' => 'ベナン', 'Burkina Faso' => 'ブルキナファソ', 'Burundi' => 'ブルンジ', 'Cape Verde' => 'カーボベルデ', 'Comoros' => 'コモロ',
        'Djibouti' => 'ジブチ', 'Equatorial Guinea' => '赤道ギニア', 'Eritrea' => 'エリトリア', 'Gabon' => 'ガボン', 'Guinea' => 'ギニア',
        'Guinea-Bissau' => 'ギニアビサウ', 'Lesotho' => 'レソト', 'Liberia' => 'リベリア', 'Madagascar' => 'マダガスカル', 'Malawi' => 'マラウィ',
        'Mauritania' => 'モーリタニア', 'Mauritius' => 'モーリシャス', 'Mozambique' => 'モザンビーク', 'Rwanda' => 'ルワンダ', 'Sao Tome and Principe' => 'サントメ・プリンシペ',
        'Seychelles' => 'セーシェル', 'Sierra Leone' => 'シエラレオネ', 'Somalia' => 'ソマリア', 'South Sudan' => '南スーダン', 'Tanzania' => 'タンザニア',
        'Togo' => 'トーゴ', 'Uganda' => 'ウガンダ',
        
        // アメリカ
        'United States' => 'アメリカ合衆国', 'Canada' => 'カナダ', 'Mexico' => 'メキシコ', 'Brazil' => 'ブラジル', 'Argentina' => 'アルゼンチン',
        'Chile' => 'チリ', 'Peru' => 'ペルー', 'Colombia' => 'コロンビア', 'Venezuela' => 'ベネズエラ', 'Ecuador' => 'エクアドル',
        'Bolivia' => 'ボリビア', 'Paraguay' => 'パラグアイ', 'Uruguay' => 'ウルグアイ', 'Guyana' => 'ガイアナ', 'Suriname' => 'スリナム',
        'Antigua and Barbuda' => 'アンティグア・バーブーダ', 'Bahamas' => 'バハマ', 'Barbados' => 'バルバドス', 'Belize' => 'ベリーズ', 'Costa Rica' => 'コスタリカ',
        'Cuba' => 'キューバ', 'Dominica' => 'ドミニカ', 'Dominican Republic' => 'ドミニカ共和国', 'El Salvador' => 'エルサルバドル', 'Grenada' => 'グレナダ',
        'Guatemala' => 'グアテマラ', 'Haiti' => 'ハイチ', 'Honduras' => 'ホンジュラス', 'Jamaica' => 'ジャマイカ', 'Nicaragua' => 'ニカラグア',
        'Panama' => 'パナマ', 'Saint Kitts and Nevis' => 'セントクリストファー・ネイビス', 'Saint Lucia' => 'セントルシア', 'Saint Vincent and the Grenadines' => 'セントビンセント・グレナディーン',
        'Trinidad and Tobago' => 'トリニダード・トバゴ',
        
        // オセアニア
        'Australia' => 'オーストラリア', 'New Zealand' => 'ニュージーランド', 'Papua New Guinea' => 'パプアニューギニア', 'Fiji' => 'フィジー',
        'Solomon Islands' => 'ソロモン諸島', 'Vanuatu' => 'バヌアツ', 'Kiribati' => 'キリバス', 'Marshall Islands' => 'マーシャル諸島',
        'Micronesia' => 'ミクロネシア', 'Nauru' => 'ナウル', 'Palau' => 'パラオ', 'Samoa' => 'サモア', 'Tonga' => 'トンガ', 'Tuvalu' => 'ツバル'
    ];
    
    return $japaneseNames[$countryName] ?? $countryName;
}

// 複数の国情報を一括取得
function getMultipleCountries($countryNames) {
    $results = [];
    foreach ($countryNames as $countryName) {
        $countryInfo = getCountryInfo($countryName);
        if ($countryInfo) {
            $results[] = $countryInfo;
        }
    }
    return $results;
}

// 地域別の国リストを取得
function getCountriesByRegion($region) {
    $apiUrl = "https://restcountries.com/v2/region/" . urlencode($region);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 && $response) {
        $data = json_decode($response, true);
        $formattedData = [];
        foreach ($data as $country) {
            $formattedData[] = formatCountryDataV2($country);
        }
        return $formattedData;
    }
    
    return [];
}

// 天候情報を取得する関数（OpenWeatherMap API使用）
function getWeatherInfo($city, $countryCode) {
    $apiKey = 'YOUR_API_KEY'; // OpenWeatherMapのAPIキーを設定
    $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q={$city},{$countryCode}&appid={$apiKey}&units=metric&lang=ja";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 && $response) {
        $data = json_decode($response, true);
        return [
            'temperature' => round($data['main']['temp']),
            'description' => $data['weather'][0]['description'],
            'humidity' => $data['main']['humidity'],
            'wind_speed' => $data['wind']['speed'],
            'icon' => $data['weather'][0]['icon']
        ];
    }
    
    return null;
}

// エラーハンドリング付きの国情報取得
function getCountryInfoSafe($countryName) {
    try {
        $countryInfo = getCountryInfo($countryName);
        if ($countryInfo) {
            return [
                'success' => true,
                'data' => $countryInfo
            ];
        } else {
            return [
                'success' => false,
                'error' => '国情報が見つかりませんでした'
            ];
        }
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => 'API呼び出しエラー: ' . $e->getMessage()
        ];
    }
}

// キャッシュ機能付きの国情報取得
function getCountryInfoCached($countryName, $cacheTime = 3600) {
    $cacheFile = "cache/country_{$countryName}.json";
    
    // キャッシュディレクトリを作成
    if (!is_dir('cache')) {
        mkdir('cache', 0755, true);
    }
    
    // キャッシュが有効かチェック
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
        $cachedData = json_decode(file_get_contents($cacheFile), true);
        return $cachedData;
    }
    
    // APIからデータを取得
    $countryInfo = getCountryInfo($countryName);
    if ($countryInfo) {
        // キャッシュに保存
        file_put_contents($cacheFile, json_encode($countryInfo));
        return $countryInfo;
    }
    
    return null;
}

// キャッシュ機能付きの全国取得
function getAllCountriesCached($cacheTime = 3600) {
    $cacheFile = "cache/all_countries.json";
    
    // キャッシュディレクトリを作成
    if (!is_dir('cache')) {
        mkdir('cache', 0755, true);
    }
    
    // デバッグ用：キャッシュファイルの存在確認
    if (file_exists($cacheFile)) {
        $cacheAge = time() - filemtime($cacheFile);
        error_log("Cache file exists, age: {$cacheAge} seconds");
    } else {
        error_log("Cache file does not exist");
    }
    
    // キャッシュが有効かチェック
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
        $cachedData = json_decode(file_get_contents($cacheFile), true);
        error_log("Using cached data, countries count: " . count($cachedData));
        return $cachedData;
    }
    
    // APIからデータを取得
    error_log("Fetching data from API...");
    $countries = getAllCountries();
    error_log("API response, countries count: " . count($countries));
    
    if ($countries) {
        // キャッシュに保存
        file_put_contents($cacheFile, json_encode($countries));
        error_log("Data cached successfully");
        return $countries;
    }
    
    error_log("No data received from API");
    return [];
}
?> 