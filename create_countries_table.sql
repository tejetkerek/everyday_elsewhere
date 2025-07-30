-- 国情報テーブルの作成
CREATE TABLE IF NOT EXISTS countries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(100) NOT NULL,
    name_jp VARCHAR(100),
    flag_emoji VARCHAR(10),
    capital VARCHAR(100),
    population BIGINT,
    region VARCHAR(50),
    subregion VARCHAR(50),
    area DECIMAL(15,2),
    languages JSON,
    currencies JSON,
    timezones JSON,
    borders JSON,
    flag_url VARCHAR(255),
    coat_of_arms_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_region (region),
    INDEX idx_name_en (name_en)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 訪問履歴テーブルの作成
CREATE TABLE IF NOT EXISTS country_visits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    country_id INT,
    visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    stamped_at TIMESTAMP NULL,
    FOREIGN KEY (country_id) REFERENCES countries(id),
    UNIQUE KEY unique_user_country (user_id, country_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- サンプルデータの挿入（主要8か国）
INSERT INTO countries (name_en, name_jp, flag_emoji, capital, population, region, subregion, area, languages, currencies, timezones) VALUES
('Japan', '日本', '🇯🇵', 'Tokyo', 125700000, 'Asia', 'Eastern Asia', 377975, '["Japanese"]', '["JPY"]', '["UTC+09:00"]'),
('Vietnam', 'ベトナム', '🇻🇳', 'Hanoi', 97338579, 'Asia', 'South-Eastern Asia', 331212, '["Vietnamese"]', '["VND"]', '["UTC+07:00"]'),
('France', 'フランス', '🇫🇷', 'Paris', 67391582, 'Europe', 'Western Europe', 551695, '["French"]', '["EUR"]', '["UTC+01:00"]'),
('Italy', 'イタリア', '🇮🇹', 'Rome', 60461826, 'Europe', 'Southern Europe', 301336, '["Italian"]', '["EUR"]', '["UTC+01:00"]'),
('Brazil', 'ブラジル', '🇧🇷', 'Brasília', 212559417, 'Americas', 'South America', 8515767, '["Portuguese"]', '["BRL"]', '["UTC-05:00", "UTC-04:00", "UTC-03:00", "UTC-02:00"]'),
('Egypt', 'エジプト', '🇪🇬', 'Cairo', 102334404, 'Africa', 'Northern Africa', 1002450, '["Arabic"]', '["EGP"]', '["UTC+02:00"]'),
('India', 'インド', '🇮🇳', 'New Delhi', 1380004385, 'Asia', 'Southern Asia', 3287590, '["Hindi", "English"]', '["INR"]', '["UTC+05:30"]'),
('South Korea', '韓国', '🇰🇷', 'Seoul', 51269185, 'Asia', 'Eastern Asia', 100210, '["Korean"]', '["KRW"]', '["UTC+09:00"]'); 