<?php
namespace Modules\Admin;

use App\DB;

class AuditLog
{
    public static function record(int $userId, string $action, string $details): void
    {
        $pdo = DB::connection();
        $stmt = $pdo->prepare('INSERT INTO ' . self::table('audit_log') . ' (user_id, action, details) VALUES (:user_id, :action, :details)');
        $stmt->execute([
            'user_id' => $userId,
            'action' => $action,
            'details' => $details,
        ]);
    }

    private static function table(string $name): string
    {
        return \App\config('database.table_prefix', '') . $name;
    }
}
