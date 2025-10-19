CREATE TABLE IF NOT EXISTS {{prefix}}users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(190) UNIQUE,
    username VARCHAR(190),
    password VARCHAR(255),
    role VARCHAR(50) DEFAULT 'Player',
    two_factor_secret VARCHAR(255) NULL,
    is_demo TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}sessions (
    id CHAR(64) PRIMARY KEY,
    user_id INT,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES {{prefix}}users(id)
);

CREATE TABLE IF NOT EXISTS {{prefix}}audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255),
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT,
    name VARCHAR(255),
    vocation VARCHAR(100),
    level INT,
    world VARCHAR(100),
    deletion_requested_at DATETIME NULL,
    is_demo TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}guilds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    motd TEXT,
    is_demo TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}guild_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guild_id INT,
    character_id INT,
    rank VARCHAR(100),
    is_demo TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}market_offers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT,
    buyer_id INT NULL,
    type ENUM('buy','sell','auction'),
    subject_type ENUM('item','character'),
    subject_id INT,
    price INT,
    status ENUM('active','filled','canceled','expired') DEFAULT 'active',
    escrow_reference VARCHAR(64) NULL,
    expires_at DATETIME NULL,
    is_demo TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}market_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    offer_id INT,
    event VARCHAR(100),
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}item_serials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    serial VARCHAR(64) UNIQUE,
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}character_transfers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT,
    seller_id INT,
    buyer_id INT,
    status VARCHAR(50),
    escrow_reference VARCHAR(64),
    is_demo TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}pvp_kills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    killer_id INT,
    victim_id INT,
    occurred_at DATETIME,
    location VARCHAR(255),
    is_demo TINYINT(1) DEFAULT 0
);

CREATE TABLE IF NOT EXISTS {{prefix}}highscores_cache (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cache_key VARCHAR(190) UNIQUE,
    payload LONGTEXT,
    cached_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    type ENUM('image','video'),
    path VARCHAR(255),
    uploaded_by INT,
    approved TINYINT(1) DEFAULT 0,
    approved_by INT NULL,
    is_demo TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}news_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    body LONGTEXT,
    published_at DATETIME,
    is_demo TINYINT(1) DEFAULT 0
);

CREATE TABLE IF NOT EXISTS {{prefix}}settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(190) UNIQUE,
    setting_value TEXT
);

CREATE TABLE IF NOT EXISTS {{prefix}}server_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    server_name VARCHAR(255),
    status VARCHAR(50),
    players INT NULL,
    checked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
