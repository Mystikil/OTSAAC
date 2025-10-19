<?php
declare(strict_types=1);

namespace App\Modules\Accounts;

use App\Auth;
use App\Controller;
use App\Security;
use function App\csrf_token;
use function App\verify_csrf;
use function App\db;

final class AccountsController extends Controller
{
    public function login(): string
    {
        return $this->render('Accounts/views/login', []);
    }

    public function processLogin(): string
    {
        Security::requireCsrf();
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        if (!$email || !$password || !Auth::attempt($email, $password)) {
            $_SESSION['_alerts'][] = ['type' => 'danger', 'message' => 'Invalid credentials'];
            return $this->render('Accounts/views/login', []);
        }
        $this->redirect('/account');
    }

    public function register(): string
    {
        return $this->render('Accounts/views/register', []);
    }

    public function processRegister(): string
    {
        Security::requireCsrf();
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!$email || $username === '' || strlen($password) < 8) {
            $_SESSION['_alerts'][] = ['type' => 'danger', 'message' => 'Registration failed validation'];
            return $this->render('Accounts/views/register', []);
        }
        $stmt = db()->prepare('INSERT INTO users (email, username, password, role, is_demo) VALUES (:email, :username, :password, :role, 0)');
        $stmt->execute([
            'email' => $email,
            'username' => $username,
            'password' => Security::hashPassword($password, $this->config),
            'role' => 'Player',
        ]);
        $_SESSION['_alerts'][] = ['type' => 'success', 'message' => 'Account created. You may login.'];
        $this->redirect('/login');
    }

    public function logout(): string
    {
        Security::requireCsrf();
        Auth::logout();
        $this->redirect('/');
    }

    public function account(): string
    {
        return $this->render('Accounts/views/account', ['user' => Auth::user()]);
    }

    public function updateProfile(): string
    {
        Security::requireCsrf();
        $user = Auth::user();
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if ($user && $email) {
            $stmt = db()->prepare('UPDATE users SET email = :email WHERE id = :id');
            $stmt->execute(['email' => $email, 'id' => $user['id']]);
            $_SESSION['_alerts'][] = ['type' => 'success', 'message' => 'Profile updated'];
        }
        $this->redirect('/account');
    }

    public function updateSecurity(): string
    {
        Security::requireCsrf();
        $user = Auth::user();
        $password = $_POST['password'] ?? '';
        if ($user && strlen($password) >= 8) {
            $stmt = db()->prepare('UPDATE users SET password = :password WHERE id = :id');
            $stmt->execute(['password' => Security::hashPassword($password, $this->config), 'id' => $user['id']]);
            $_SESSION['_alerts'][] = ['type' => 'success', 'message' => 'Password updated'];
        }
        $this->redirect('/account');
    }
}
