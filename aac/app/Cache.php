<?php
namespace App;

class Cache
{
    private string $path;
    private static ?self $instance = null;

    public function __construct()
    {
        $this->path = BASE_PATH . '/storage/cache';
    }

    public static function instance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get(string $key)
    {
        $file = $this->file($key);
        if (!file_exists($file)) {
            return null;
        }
        $data = json_decode(file_get_contents($file), true);
        if (!$data || $data['expires_at'] < time()) {
            @unlink($file);
            return null;
        }
        return $data['value'];
    }

    public function set(string $key, $value, int $ttl): void
    {
        $file = $this->file($key);
        file_put_contents($file, json_encode(['value' => $value, 'expires_at' => time() + $ttl]));
    }

    private function file(string $key): string
    {
        return $this->path . '/' . md5($key) . '.cache';
    }
}
