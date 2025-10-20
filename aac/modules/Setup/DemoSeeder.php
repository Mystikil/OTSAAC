<?php
namespace Modules\Setup;

use DateInterval;
use DateTimeImmutable;
use PDO;

class DemoSeeder
{
    public function __construct(private PDO $pdo, private string $prefix)
    {
    }

    public function seed(): void
    {
        $this->seedUsers();
        $this->seedCharacters();
        $this->seedGuilds();
        $this->seedMarket();
        $this->seedPvP();
        $this->seedMedia();
        $this->seedNews();
    }

    private function seedUsers(): void
    {
        for ($i = 1; $i <= 50; $i++) {
            $stmt = $this->pdo->prepare('INSERT INTO ' . $this->prefix . 'users (email, username, password, role, is_demo) VALUES (:email, :username, :password, :role, 1) ON DUPLICATE KEY UPDATE username=VALUES(username)');
            $stmt->execute([
                'email' => "demo{$i}@example.com",
                'username' => "DemoUser{$i}",
                'password' => password_hash('demoPass123!', PASSWORD_ARGON2ID),
                'role' => 'Player',
            ]);
        }
    }

    private function seedCharacters(): void
    {
        $worlds = ['Aurora', 'Chronos'];
        $vocations = ['Knight', 'Sorcerer', 'Paladin', 'Druid'];
        for ($i = 1; $i <= 200; $i++) {
            $stmt = $this->pdo->prepare('INSERT INTO ' . $this->prefix . 'characters (account_id, name, vocation, level, world, is_demo) VALUES (:account_id, :name, :vocation, :level, :world, 1)');
            $stmt->execute([
                'account_id' => rand(1, 50),
                'name' => 'DemoCharacter' . $i,
                'vocation' => $vocations[array_rand($vocations)],
                'level' => rand(10, 250),
                'world' => $worlds[array_rand($worlds)],
            ]);
        }
    }

    private function seedGuilds(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $stmt = $this->pdo->prepare('INSERT INTO ' . $this->prefix . 'guilds (name, motd, is_demo) VALUES (:name, :motd, 1)');
            $stmt->execute([
                'name' => 'Demo Guild ' . $i,
                'motd' => 'Welcome to Demo Guild ' . $i,
            ]);
        }
    }

    private function seedMarket(): void
    {
        for ($i = 1; $i <= 120; $i++) {
            $stmt = $this->pdo->prepare('INSERT INTO ' . $this->prefix . 'market_offers (seller_id, type, item_name, price, status, is_demo) VALUES (:seller_id, :type, :item, :price, :status, 1)');
            $stmt->execute([
                'seller_id' => rand(1, 50),
                'type' => ['buy', 'sell', 'auction'][array_rand(['buy', 'sell', 'auction'])],
                'item' => 'Item #' . $i,
                'price' => rand(100, 10000),
                'status' => ['active', 'filled', 'canceled'][array_rand(['active', 'filled', 'canceled'])],
            ]);
        }
    }

    private function seedPvP(): void
    {
        $now = new DateTimeImmutable();
        for ($i = 1; $i <= 250; $i++) {
            $stmt = $this->pdo->prepare('INSERT INTO ' . $this->prefix . 'pvp_kills (killer, victim, location, occurred_at, is_demo) VALUES (:killer, :victim, :location, :occurred_at, 1)');
            $stmt->execute([
                'killer' => 'DemoCharacter' . rand(1, 200),
                'victim' => 'DemoCharacter' . rand(1, 200),
                'location' => 'Arena ' . rand(1, 5),
                'occurred_at' => $now->sub(new DateInterval('PT' . rand(1, 720) . 'H'))->format('Y-m-d H:i:s'),
            ]);
        }
    }

    private function seedMedia(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $stmt = $this->pdo->prepare('INSERT INTO ' . $this->prefix . 'media (title, type, path, url, uploaded_by, is_demo) VALUES (:title, :type, :path, :url, :uploaded_by, 1)');
            $stmt->execute([
                'title' => 'Demo Media ' . $i,
                'type' => 'image',
                'path' => 'img/placeholders/demo-' . $i . '.png',
                'url' => null,
                'uploaded_by' => rand(1, 50),
            ]);
        }
    }

    private function seedNews(): void
    {
        $now = new DateTimeImmutable();
        for ($i = 1; $i <= 10; $i++) {
            $stmt = $this->pdo->prepare('INSERT INTO ' . $this->prefix . 'news (title, slug, body, published_at, author_id, is_demo) VALUES (:title, :slug, :body, :published_at, :author_id, 1)');
            $stmt->execute([
                'title' => 'Demo News ' . $i,
                'slug' => 'demo-news-' . $i,
                'body' => 'Sample news content #' . $i,
                'published_at' => $now->format('Y-m-d H:i:s'),
                'author_id' => 1,
            ]);
        }
    }
}
