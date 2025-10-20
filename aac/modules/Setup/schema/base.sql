CREATE TABLE IF NOT EXISTS {{prefix}}users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE,
    username VARCHAR(50),
    password VARCHAR(255),
    role VARCHAR(20) DEFAULT 'Player',
    is_demo TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}sessions (
    id CHAR(64) PRIMARY KEY,
    user_id INT,
    ip VARCHAR(45),
    user_agent VARCHAR(255),
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES {{prefix}}users(id)
);

CREATE TABLE IF NOT EXISTS {{prefix}}audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100),
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT,
    name VARCHAR(100),
    vocation VARCHAR(50),
    level INT DEFAULT 1,
    world VARCHAR(100),
    is_demo TINYINT(1) DEFAULT 0,
    delete_at DATETIME NULL,
    locked TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}guilds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    motd TEXT,
    is_demo TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}guild_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guild_id INT,
    character_id INT,
    rank VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150),
    type ENUM('image','video'),
    path VARCHAR(255),
    url VARCHAR(255),
    uploaded_by INT,
    approved_by INT NULL,
    is_demo TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}market_offers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT,
    buyer_id INT NULL,
    type ENUM('buy','sell','auction'),
    item_name VARCHAR(100),
    item_serial VARCHAR(100) NULL,
    price INT,
    fee INT DEFAULT 0,
    status ENUM('active','filled','canceled','expired') DEFAULT 'active',
    expires_at DATETIME NULL,
    is_character TINYINT(1) DEFAULT 0,
    character_id INT NULL,
    is_demo TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}market_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    offer_id INT,
    action VARCHAR(50),
    performed_by INT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}item_serials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100),
    serial VARCHAR(100) UNIQUE,
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS {{prefix}}pvp_kills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    killer VARCHAR(100),
    victim VARCHAR(100),
    location VARCHAR(100),
    occurred_at DATETIME,
    is_demo TINYINT(1) DEFAULT 0
);

CREATE TABLE IF NOT EXISTS {{prefix}}highscores_cache (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50),
    payload JSON,
    cached_at DATETIME,
    ttl INT
);

CREATE TABLE IF NOT EXISTS {{prefix}}settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE,
    value TEXT
);

CREATE TABLE IF NOT EXISTS {{prefix}}news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150),
    slug VARCHAR(150),
    body TEXT,
    published_at DATETIME,
    author_id INT,
    is_demo TINYINT(1) DEFAULT 0
);
