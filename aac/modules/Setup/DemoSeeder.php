<?php
declare(strict_types=1);

namespace App\Modules\Setup;

use PDO;

final class DemoSeeder
{
    public function __construct(private PDO $pdo, private string $prefix)
    {
    }

    public function run(): void
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
        $stmt = $this->pdo->prepare("INSERT INTO {$this->prefix}users (email, username, password, role, is_demo) VALUES (:email, :username, :password, :role, 1)");
        for ($i = 1; $i <= 50; $i++) {
            $stmt->execute([
                'email' => "demo{$i}@example.com",
                'username' => "DemoUser{$i}",
                'password' => password_hash('password123', PASSWORD_ARGON2ID),
                'role' => 'Player',
            ]);
        }
    }

    private function seedCharacters(): void
    {
        $vocations = ['Knight', 'Druid', 'Paladin', 'Sorcerer'];
        $stmt = $this->pdo->prepare("INSERT INTO {$this->prefix}characters (account_id, name, vocation, level, world, is_demo) VALUES (:account, :name, :vocation, :level, :world, 1)");
        for ($i = 1; $i <= 200; $i++) {
            $stmt->execute([
                'account' => rand(1, 50),
                'name' => 'Demo Character ' . $i,
                'vocation' => $vocations[array_rand($vocations)],
                'level' => rand(10, 300),
                'world' => 'Demo World',
            ]);
        }
    }

    private function seedGuilds(): void
    {
        $guildStmt = $this->pdo->prepare("INSERT INTO {$this->prefix}guilds (name, motd, is_demo) VALUES (:name, :motd, 1)");
        $memberStmt = $this->pdo->prepare("INSERT INTO {$this->prefix}guild_members (guild_id, character_id, rank, is_demo) VALUES (:guild, :character, :rank, 1)");
        for ($i = 1; $i <= 5; $i++) {
            $guildStmt->execute([
                'name' => 'Demo Guild ' . $i,
                'motd' => 'Welcome to Demo Guild ' . $i,
            ]);
            $guildId = (int)$this->pdo->lastInsertId();
            for ($j = 0; $j < 10; $j++) {
                $memberStmt->execute([
                    'guild' => $guildId,
                    'character' => rand(1, 200),
                    'rank' => $j === 0 ? 'Leader' : 'Member',
                ]);
            }
        }
    }

    private function seedMarket(): void
    {
        $offerStmt = $this->pdo->prepare("INSERT INTO {$this->prefix}market_offers (seller_id, type, subject_type, subject_id, price, status, expires_at, is_demo) VALUES (:seller, :type, :subject_type, :subject_id, :price, :status, :expires, 1)");
        for ($i = 1; $i <= 100; $i++) {
            $offerStmt->execute([
                'seller' => rand(1, 50),
                'type' => ['buy', 'sell', 'auction'][rand(0, 2)],
                'subject_type' => rand(0, 1) ? 'item' : 'character',
                'subject_id' => rand(1, 200),
                'price' => rand(100, 10000),
                'status' => ['active', 'filled', 'canceled'][rand(0, 2)],
                'expires' => date('Y-m-d H:i:s', strtotime('+7 days')),
            ]);
        }
    }

    private function seedPvP(): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->prefix}pvp_kills (killer_id, victim_id, occurred_at, location, is_demo) VALUES (:killer, :victim, :occurred_at, :location, 1)");
        for ($i = 1; $i <= 250; $i++) {
            $stmt->execute([
                'killer' => rand(1, 200),
                'victim' => rand(1, 200),
                'occurred_at' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 60) . ' days')),
                'location' => 'Demo Zone ' . rand(1, 5),
            ]);
        }
    }

    private function seedMedia(): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->prefix}media (title, type, path, uploaded_by, approved, is_demo) VALUES (:title, :type, :path, :uploaded_by, :approved, 1)");
        for ($i = 1; $i <= 20; $i++) {
            $stmt->execute([
                'title' => 'Demo Media ' . $i,
                'type' => 'image',
                'path' => 'assets/img/placeholders/placeholder' . (($i % 5) + 1) . '.png',
                'uploaded_by' => rand(1, 50),
                'approved' => 1,
            ]);
        }
    }

    private function seedNews(): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->prefix}news_posts (title, slug, body, published_at, is_demo) VALUES (:title, :slug, :body, :published_at, 1)");
        for ($i = 1; $i <= 10; $i++) {
            $stmt->execute([
                'title' => 'Demo Update ' . $i,
                'slug' => 'demo-update-' . $i,
                'body' => 'This is a demo news article filled with placeholder content to show site activity.',
                'published_at' => date('Y-m-d H:i:s', strtotime('-' . $i . ' days')),
            ]);
        }
    }
}
