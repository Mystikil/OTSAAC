<?php
declare(strict_types=1);

namespace App;

final class Cache
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = rtrim($path, '/');
        if (!is_dir($this->path)) {
            mkdir($this->path, 0775, true);
        }
    }

    public function get(string $key, int $ttl, callable $callback): mixed
    {
        $file = $this->path . '/' . md5($key) . '.cache.php';
        if (file_exists($file) && (filemtime($file) + $ttl) > time()) {
            return unserialize((string)file_get_contents($file));
        }
        $value = $callback();
        file_put_contents($file, serialize($value));
        return $value;
    }

    public function forget(string $key): void
    {
        $file = $this->path . '/' . md5($key) . '.cache.php';
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
