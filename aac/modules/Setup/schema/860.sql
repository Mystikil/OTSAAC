CREATE TABLE IF NOT EXISTS {{prefix}}skill_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT,
    skill VARCHAR(50),
    value INT,
    updated_at DATETIME
);
