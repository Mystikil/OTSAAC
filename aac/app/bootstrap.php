<?php
declare(strict_types=1);

namespace App;

require_once __DIR__ . '/Helpers.php';
require_once __DIR__ . '/Security.php';
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/Cache.php';
require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/View.php';
require_once __DIR__ . '/Auth.php';
require_once __DIR__ . '/Templating/TemplateAdapter.php';

final class Bootstrap
{
    public static function run(): void
    {
        session_start();

        $config = self::loadConfig();
        date_default_timezone_set($config['app']['timezone'] ?? 'UTC');

        if (!isset($GLOBALS['__route_map'])) {
            $GLOBALS['__route_map'] = [];
        }

        $router = new Router($config);
        self::registerRoutes($router, $config);
        $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
    }

    private static function loadConfig(): array
    {
        $configDir = dirname(__DIR__) . '/config';
        return [
            'app' => require $configDir . '/app.php',
            'database' => require $configDir . '/database.php',
            'security' => require $configDir . '/security.php',
            'features' => require $configDir . '/features.php',
            'status' => require $configDir . '/status.php',
            'templates' => require $configDir . '/templates.php',
        ];
    }

    private static function registerRoutes(Router $router, array $config): void
    {
        $isInstalled = self::isInstalled($config['app']);
        if (!$isInstalled && str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/setup')) {
            $controller = new \App\Modules\Setup\SetupController($config);
            $router->get('/setup', [$controller, 'index']);
            $router->post('/setup/eula', [$controller, 'acceptEula']);
            $router->post('/setup/database', [$controller, 'saveDatabase']);
            $router->post('/setup/admin', [$controller, 'createAdmin']);
            $router->post('/setup/options', [$controller, 'saveOptions']);
            $router->post('/setup/demo', [$controller, 'seedDemo']);
            $router->post('/setup/finish', [$controller, 'finish']);
            return;
        }

        if (!$isInstalled) {
            header('Location: /setup');
            exit;
        }

        $router->middleware('auth', fn(): bool => Auth::check());

        register_route('home', '/');
        $router->get('/', [\App\Modules\Status\StatusController::class, 'home']);

        register_route('status.index', '/status');
        $router->get('/status', [\App\Modules\Status\StatusController::class, 'status']);

        register_route('login', '/login');
        $router->get('/login', [\App\Modules\Accounts\AccountsController::class, 'login']);
        $router->post('/login', [\App\Modules\Accounts\AccountsController::class, 'processLogin']);

        register_route('register', '/register');
        $router->get('/register', [\App\Modules\Accounts\AccountsController::class, 'register']);
        $router->post('/register', [\App\Modules\Accounts\AccountsController::class, 'processRegister']);

        $router->withMiddleware('auth', function (Router $router): void {
            $router->post('/logout', [\App\Modules\Accounts\AccountsController::class, 'logout']);
            $router->get('/account', [\App\Modules\Accounts\AccountsController::class, 'account']);
            $router->post('/account/profile', [\App\Modules\Accounts\AccountsController::class, 'updateProfile']);
            $router->post('/account/security', [\App\Modules\Accounts\AccountsController::class, 'updateSecurity']);
            $router->get('/characters', [\App\Modules\Characters\CharactersController::class, 'index']);
            $router->post('/characters/create', [\App\Modules\Characters\CharactersController::class, 'create']);
            $router->post('/characters/delete', [\App\Modules\Characters\CharactersController::class, 'delete']);
            $router->post('/characters/cancel-delete', [\App\Modules\Characters\CharactersController::class, 'cancelDelete']);
            $router->post('/media/upload', [\App\Modules\Media\MediaController::class, 'upload']);
            $router->post('/market/offer', [\App\Modules\Market\MarketController::class, 'create']);
            $router->get('/admin', [\App\Modules\Admin\AdminController::class, 'dashboard']);
            $router->get('/admin/users', [\App\Modules\Admin\AdminController::class, 'users']);
            $router->post('/admin/users/role', [\App\Modules\Admin\AdminController::class, 'updateRole']);
            $router->post('/admin/media/moderate', [\App\Modules\Admin\AdminController::class, 'moderateMedia']);
        });

        register_route('market.index', '/market');
        $router->get('/market', [\App\Modules\Market\MarketController::class, 'index']);
        $router->get('/market/offers', [\App\Modules\Market\MarketController::class, 'offers']);
        $router->get('/market/offer/(?P<id>\\d+)', [\App\Modules\Market\MarketController::class, 'show']);

        register_route('highscores.index', '/highscores');
        $router->get('/highscores', [\App\Modules\Highscores\HighscoresController::class, 'index']);

        register_route('guilds.index', '/guilds');
        $router->get('/guilds', [\App\Modules\Guilds\GuildsController::class, 'index']);
        $router->get('/guild/(?P<id>\\d+)', [\App\Modules\Guilds\GuildsController::class, 'show']);

        register_route('media.index', '/media');
        $router->get('/media', [\App\Modules\Media\MediaController::class, 'index']);

        register_route('pvp.index', '/pvp');
        $router->get('/pvp', [\App\Modules\PvP\PvPController::class, 'index']);
    }

    private static function isInstalled(array $appConfig): bool
    {
        $flagFile = dirname(__DIR__) . '/storage/.installed';
        return file_exists($flagFile) || !empty($appConfig['installed']);
    }
}
