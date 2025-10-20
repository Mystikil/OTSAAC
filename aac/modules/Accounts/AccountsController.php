<?php
namespace Modules\Accounts;

use App\Auth;
use App\Controller;
use App\DB;
use App\Security;

class AccountsController extends Controller
{
    public function showLogin(): string
    {
        return $this->view('Accounts/views/login');
    }

    public function login(): string
    {
        $this->validateCsrf();
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        if (!$email || !$password) {
            $_SESSION['flash']['danger'][] = 'Invalid credentials.';
            return $this->view('Accounts/views/login');
        }
        if (!Security::requireRateLimit('login_' . $email)) {
            $_SESSION['flash']['danger'][] = 'Too many attempts. Try again later.';
            return $this->view('Accounts/views/login');
        }
        if (Auth::attempt($email, $password)) {
            $_SESSION['flash']['success'][] = 'Welcome back!';
            $this->redirect('/account');
        }
        $_SESSION['flash']['danger'][] = 'Invalid credentials.';
        return $this->view('Accounts/views/login');
    }

    public function showRegister(): string
    {
        return $this->view('Accounts/views/register');
    }

    public function register(): string
    {
        $this->validateCsrf();
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!$email || strlen($username) < 3 || strlen($password) < 8) {
            $_SESSION['flash']['danger'][] = 'Please provide valid account information.';
            return $this->view('Accounts/views/register');
        }
        $pdo = DB::connection();
        $stmt = $pdo->prepare('INSERT INTO ' . $this->table('users') . ' (email, username, password, role) VALUES (:email, :username, :password, :role)');
        try {
            $stmt->execute([
                'email' => $email,
                'username' => $username,
                'password' => Security::hashPassword($password),
                'role' => 'Player',
            ]);
        } catch (\PDOException $e) {
            $_SESSION['flash']['danger'][] = 'Email already in use.';
            return $this->view('Accounts/views/register');
        }
        $_SESSION['flash']['success'][] = 'Account created. You can now log in.';
        $this->redirect('/login');
        return '';
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/');
    }

    public function profile(): string
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        $user = Auth::user();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
            if ($email) {
                $pdo = DB::connection();
                $stmt = $pdo->prepare('UPDATE ' . $this->table('users') . ' SET email = :email WHERE id = :id');
                $stmt->execute(['email' => $email, 'id' => $user['id']]);
                $_SESSION['flash']['success'][] = 'Profile updated.';
            }
        }
        return $this->view('Accounts/views/profile', ['user' => Auth::user()]);
    }

    private function table(string $name): string
    {
        return \App\config('database.table_prefix', '') . $name;
    }
}
