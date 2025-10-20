<?php
namespace Modules\Market;

use App\Auth;
use App\Controller;
use App\DB;

class MarketController extends Controller
{
    public function index(): string
    {
        $pdo = DB::connection();
        $stmt = $pdo->query('SELECT * FROM ' . $this->table('market_offers') . ' ORDER BY created_at DESC LIMIT 50');
        $offers = $stmt->fetchAll();
        return $this->view('Market/views/index', ['offers' => $offers]);
    }

    public function create(): string
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $pdo = DB::connection();
            $stmt = $pdo->prepare('INSERT INTO ' . $this->table('market_offers') . ' (seller_id, type, item_name, price, status) VALUES (:seller_id, :type, :item_name, :price, :status)');
            $stmt->execute([
                'seller_id' => Auth::user()['id'],
                'type' => $_POST['type'],
                'item_name' => trim($_POST['item_name']),
                'price' => (int) $_POST['price'],
                'status' => 'active',
            ]);
            $_SESSION['flash']['success'][] = 'Offer created.';
            $this->redirect('/market');
        }
        return $this->view('Market/views/create');
    }

    public function show(int $id): string
    {
        $pdo = DB::connection();
        $stmt = $pdo->prepare('SELECT * FROM ' . $this->table('market_offers') . ' WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $offer = $stmt->fetch();
        if (!$offer) {
            http_response_code(404);
            return $this->view('errors/404', ['path' => '/market/offer/' . $id]);
        }
        return $this->view('Market/views/show', ['offer' => $offer]);
    }

    private function table(string $name): string
    {
        return \App\config('database.table_prefix', '') . $name;
    }
}
