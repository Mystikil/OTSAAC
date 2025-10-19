<?php
declare(strict_types=1);

namespace App\Modules\Admin;

use function App\db;

final class AuditLog
{
    public static function record(int $userId, string $action, array $metadata = []): void
    {
        $stmt = db()->prepare('INSERT INTO audit_log (user_id, action, metadata) VALUES (:user, :action, :metadata)');
        $stmt->execute([
            'user' => $userId,
            'action' => $action,
            'metadata' => json_encode($metadata, JSON_THROW_ON_ERROR),
        ]);
    }
}
