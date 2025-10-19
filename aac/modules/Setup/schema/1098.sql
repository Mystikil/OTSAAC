CREATE TABLE IF NOT EXISTS {{prefix}}spells (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    mana_cost INT,
    vocation VARCHAR(100)
);
