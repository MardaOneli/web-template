<?php
require_once '../core/Controller.php';
require_once '../core/Middleware.php';
require_once '../app/Models/User.php';
require_once '../includes/uuid.php';
require_once '../config/session.php';

class RegisterController extends Controller {
    public function index() {
        // Pastikan hanya guest yang bisa akses halaman register
        Middleware::guest();
        
        // Buat token CSRF jika belum ada
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Kirim token CSRF ke view agar disertakan dalam form
        $this->view('register', ['csrf_token' => $_SESSION['csrf_token'], 'title' => 'Register']);
    }

    public function register() {
        // Pastikan hanya menerima request POST
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->view('register');
            exit();
        }

        // Cek token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            Session::set('error', 'Invalid CSRF token. Please try again.');
            header("Location: /register");
            exit();
        }

        // Validasi dan sanitasi input
        if (empty($_POST['username']) || empty($_POST['password'])) {
            Session::set('error', 'Username and password are required.');
            header("Location: /register");
            exit();
        }
        
        $username = trim($_POST['username']);
        if (strlen($username) < 3 || strlen($username) > 50) {
            Session::set('error', 'Username must be between 3 and 50 characters.');
            header("Location: /register");
            exit();
        }
        // Sanitasi username untuk mencegah XSS
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

        $password_plain = $_POST['password'];
        if (strlen($password_plain) < 8) {
            Session::set('error', 'Password must be at least 8 characters.');
            header("Location: /register");
            exit();
        }
        // Hash password menggunakan algoritma BCRYPT
        $password = password_hash($password_plain, PASSWORD_BCRYPT);

        // Generate ID unik untuk user
        $id = guid();
        $role = "user";

        try {
            $userModel = new User();
            $result = $userModel->register($id, $username, $password, $role);
            if ($result) {
                // Regenerate token CSRF setelah registrasi sukses untuk mencegah replay attack
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                Session::set('success', 'Registration successful. Please login.');
                header("Location: /login");
                exit();
            } else {
                Session::set('error', 'Registration failed, please try again.');
                header("Location: /register");
                exit();
            }
        } catch (Exception $e) {
            // Log error secara internal, jangan tampilkan detail ke user
            error_log($e->getMessage());
            Session::set('error', 'An unexpected error occurred. Please try again later.');
            header("Location: /register");
            exit();
        }
    }
}
