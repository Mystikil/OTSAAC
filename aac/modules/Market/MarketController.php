<?php
declare(strict_types=1);

namespace App\Modules\Market;

use App\Auth;
use App\Controller;
use App\Security;
use DateTimeImmutable;
use function App\db;

final class MarketController extends Controller
{
    public function index(): string
    {
        $stmt = db()->query('SELECT * FROM market_offers ORDER BY created_at DESC LIMIT 20');
        $offers = $stmt->fetchAll();
        return $this->render('Market/views/index', ['offers' => $offers]);
    }

    public function offers(): string
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $stmt = db()->prepare('SELECT * FROM market_offers ORDER BY created_at DESC LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $offers = $stmt->fetchAll();
        return $this->render('Market/views/offers', ['offers' => $offers, 'page' => $page]);
    }

    public function show(string $id): string
    {
        $stmt = db()->prepare('SELECT * FROM market_offers WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $offer = $stmt->fetch();
        return $this->render('Market/views/show', ['offer' => $offer]);
    }

    public function create(): string
    {
        Security::requireCsrf();
        $user = Auth::user();
        $serial = $_POST['serial'] ?? SerialService::generate();
        if (!SerialService::validate($serial)) {
            $_SESSION['_alerts'][] = ['type' => 'danger', 'message' => 'Invalid serial'];
            $this->redirect('/market');
        }
        $stmt = db()->prepare('INSERT INTO market_offers (seller_id, type, subject_type, subject_id, price, status, expires_at, is_demo) VALUES (:seller, :type, :subject_type, :subject_id, :price, :status, :expires, 0)');
        $stmt->execute([
            'seller' => $user['id'],
            'type' => $_POST['type'] ?? 'sell',
            'subject_type' => $_POST['subject_type'] ?? 'item',
            'subject_id' => (int)($_POST['subject_id'] ?? 0),
            'price' => (int)($_POST['price'] ?? 0),
            'status' => 'active',
            'expires' => (new DateTimeImmutable($_POST['expires_at'] ?? '+7 days'))->format('Y-m-d H:i:s'),
        ]);
        $_SESSION['_alerts'][] = ['type' => 'success', 'message' => 'Offer created'];
        $this->redirect('/market');
    }
}
