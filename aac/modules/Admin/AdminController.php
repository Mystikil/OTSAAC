<?php
namespace Modules\Admin;

use App\Auth;
use App\Controller;
use App\DB;

class AdminController extends Controller
{
    public function dashboard(): string
    {
        $this->authorize();
        $pdo = DB::connection();
        $users = $pdo->query('SELECT COUNT(*) as total FROM ' . $this->table('users'))->fetch();
        $offers = $pdo->query('SELECT COUNT(*) as total FROM ' . $this->table('market_offers'))->fetch();
        $media = $pdo->query('SELECT COUNT(*) as total FROM ' . $this->table('media'))->fetch();
        return $this->view('Admin/views/dashboard', [
            'userCount' => $users['total'] ?? 0,
            'offerCount' => $offers['total'] ?? 0,
            'mediaCount' => $media['total'] ?? 0,
        ]);
    }

    private function authorize(): void
    {
        if (!Auth::check() || !in_array(Auth::user()['role'], ['Tutor', 'GM', 'Admin'])) {
            http_response_code(403);
            exit('Forbidden');
        }
    }

    private function table(string $name): string
    {
        return \App\config('database.table_prefix', '') . $name;
    }
}
