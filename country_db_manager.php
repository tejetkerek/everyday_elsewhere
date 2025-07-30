<?php
/**
 * 国情報のデータベース管理クラス
 * APIからデータを取得してDBに保存する機能
 */

require_once('funcs.php');

class CountryDBManager {
    private $pdo;
    
    public function __construct() {
        $this->pdo = db_conn();
    }
    
    /**
     * APIからデータを取得してDBに保存
     */
    public function syncCountriesFromAPI() {
        try {
            // APIからデータを取得
            $countries = $this->fetchCountriesFromAPI();
            
            if (empty($countries)) {
                error_log("No countries data received from API");
                return false;
            }
            
            // DBに保存
            $this->saveCountriesToDB($countries);
            
            error_log("Successfully synced " . count($countries) . " countries to database");
            return true;
            
        } catch (Exception $e) {
            error_log("Error syncing countries: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * APIから国データを取得
     */
    private function fetchCountriesFromAPI() {
        $apiUrl = "https://restcountries.com/v3.1/all";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            if ($data) {
                return $this->formatCountriesForDB($data);
            }
        }
        
        return [];
    }
    
    /**
     * APIデータをDB用にフォーマット
     */
    private function formatCountriesForDB($apiData) {
        $formatted = [];
        
        foreach ($apiData as $country) {
            $formatted[] = [
                'name_en' => $country['name']['common'] ?? '',
                'name_jp' => $country['translations']['jpn']['common'] ?? $country['name']['common'] ?? '',
                'flag_emoji' => $country['flags']['emoji'] ?? '',
                'capital' => $country['capital'][0] ?? '',
                'population' => $country['population'] ?? 0,
                'region' => $country['region'] ?? '',
                'subregion' => $country['subregion'] ?? '',
                'area' => $country['area'] ?? 0,
                'languages' => json_encode($country['languages'] ?? []),
                'currencies' => json_encode($country['currencies'] ?? []),
                'timezones' => json_encode($country['timezones'] ?? []),
                'borders' => json_encode($country['borders'] ?? []),
                'flag_url' => $country['flags']['png'] ?? '',
                'coat_of_arms_url' => $country['coatOfArms']['png'] ?? ''
            ];
        }
        
        return $formatted;
    }
    
    /**
     * 国データをDBに保存
     */
    private function saveCountriesToDB($countries) {
        // 既存データを削除
        $this->pdo->exec("DELETE FROM countries");
        
        // 新しいデータを挿入
        $stmt = $this->pdo->prepare("
            INSERT INTO countries (
                name_en, name_jp, flag_emoji, capital, population, 
                region, subregion, area, languages, currencies, 
                timezones, borders, flag_url, coat_of_arms_url
            ) VALUES (
                :name_en, :name_jp, :flag_emoji, :capital, :population,
                :region, :subregion, :area, :languages, :currencies,
                :timezones, :borders, :flag_url, :coat_of_arms_url
            )
        ");
        
        foreach ($countries as $country) {
            $stmt->execute($country);
        }
    }
    
    /**
     * DBからすべての国を取得
     */
    public function getAllCountriesFromDB() {
        $stmt = $this->pdo->query("SELECT * FROM countries ORDER BY region, name_en");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * 地域別に国を取得
     */
    public function getCountriesByRegion($region) {
        $stmt = $this->pdo->prepare("SELECT * FROM countries WHERE region = ? ORDER BY name_en");
        $stmt->execute([$region]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * 統計情報を取得
     */
    public function getStatistics() {
        $stats = [];
        
        // 総国数
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM countries");
        $stats['total_countries'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // 総人口
        $stmt = $this->pdo->query("SELECT SUM(population) as total FROM countries");
        $stats['total_population'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // 地域数
        $stmt = $this->pdo->query("SELECT COUNT(DISTINCT region) as total FROM countries");
        $stats['total_regions'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // 地域別統計
        $stmt = $this->pdo->query("
            SELECT region, COUNT(*) as count, SUM(population) as population 
            FROM countries 
            GROUP BY region
        ");
        $stats['region_stats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }
    
    /**
     * 訪問履歴を記録
     */
    public function recordVisit($userId, $countryId) {
        $stmt = $this->pdo->prepare("
            INSERT INTO country_visits (user_id, country_id) 
            VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE visited_at = CURRENT_TIMESTAMP
        ");
        return $stmt->execute([$userId, $countryId]);
    }
    
    /**
     * スタンプを記録
     */
    public function recordStamp($userId, $countryId) {
        $stmt = $this->pdo->prepare("
            UPDATE country_visits 
            SET stamped_at = CURRENT_TIMESTAMP 
            WHERE user_id = ? AND country_id = ?
        ");
        return $stmt->execute([$userId, $countryId]);
    }
    
    /**
     * ユーザーの訪問履歴を取得
     */
    public function getUserVisits($userId) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, cv.visited_at, cv.stamped_at
            FROM countries c
            LEFT JOIN country_visits cv ON c.id = cv.country_id AND cv.user_id = ?
            ORDER BY c.region, c.name_en
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// 使用例
if (isset($_GET['sync'])) {
    $manager = new CountryDBManager();
    if ($manager->syncCountriesFromAPI()) {
        echo "Countries synced successfully!";
    } else {
        echo "Failed to sync countries.";
    }
}
?> 