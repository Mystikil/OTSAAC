<?php
use App\Router;
use Modules\Setup\SetupController;
use Modules\Accounts\AccountsController;
use Modules\Characters\CharactersController;
use Modules\Market\MarketController;
use Modules\Highscores\HighscoresController;
use Modules\Guilds\GuildsController;
use Modules\Media\MediaController;
use Modules\PvP\PvPController;
use Modules\Status\StatusController;
use Modules\Admin\AdminController;

$router = Router::instance();

$authMiddleware = [function (string $path, string $method) {
    if (!App\Auth::check()) {
        header('Location: /login');
        exit;
    }
    return null;
}];

if (!file_exists(BASE_PATH . '/config/.installed')) {
    $router->get('/setup', [new SetupController(), 'index']);
    $router->post('/setup', [new SetupController(), 'index']);
} else {
    $router->get('/setup', [new SetupController(), 'index']);
}

$router->get('/', function () {
    return App\view('home', []);
}, 'home');

$router->get('/login', [new AccountsController(), 'showLogin']);
$router->post('/login', [new AccountsController(), 'login']);
$router->get('/register', [new AccountsController(), 'showRegister']);
$router->post('/register', [new AccountsController(), 'register']);
$router->post('/logout', [new AccountsController(), 'logout']);
$router->get('/account', [new AccountsController(), 'profile'], 'account', $authMiddleware);
$router->post('/account', [new AccountsController(), 'profile'], null, $authMiddleware);

$router->get('/characters', [new CharactersController(), 'index'], null, $authMiddleware);
$router->get('/characters/create', [new CharactersController(), 'create'], null, $authMiddleware);
$router->post('/characters/create', [new CharactersController(), 'create'], null, $authMiddleware);
$router->post('/characters/delete/{id}', function ($id) {
    return (new CharactersController())->requestDelete((int) $id);
}, null, $authMiddleware);
$router->post('/characters/cancel/{id}', function ($id) {
    return (new CharactersController())->cancelDelete((int) $id);
}, null, $authMiddleware);

$router->get('/market', [new MarketController(), 'index']);
$router->get('/market/create', [new MarketController(), 'create'], null, $authMiddleware);
$router->post('/market/create', [new MarketController(), 'create'], null, $authMiddleware);
$router->get('/market/offer/{id}', function ($id) {
    return (new MarketController())->show((int) $id);
}, 'market.offer');

$router->get('/highscores', [new HighscoresController(), 'index']);
$router->get('/guilds', [new GuildsController(), 'index']);
$router->get('/guilds/create', [new GuildsController(), 'create'], null, $authMiddleware);
$router->post('/guilds/create', [new GuildsController(), 'create'], null, $authMiddleware);
$router->get('/media', [new MediaController(), 'index']);
$router->get('/media/upload', [new MediaController(), 'upload'], null, $authMiddleware);
$router->post('/media/upload', [new MediaController(), 'upload'], null, $authMiddleware);
$router->get('/pvp', [new PvPController(), 'index']);
$router->get('/status', [new StatusController(), 'index']);
$router->get('/admin', [new AdminController(), 'dashboard'], null, $authMiddleware);
