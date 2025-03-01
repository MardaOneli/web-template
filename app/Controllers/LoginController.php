<?php
require_once '../core/Controller.php';
require_once '../core/Middleware.php';
require_once '../app/Models/User.php';
require_once '../config/session.php';
require_once '../config/rate_limit.php';

class LoginController extends Controller {
	public function index() {
		Middleware::guest();

		$usernameTemp = $_SESSION['last_username'] ?? '';
		$key = !empty($usernameTemp) ? "login_attempts_" . $usernameTemp : '';

		$isBlocked = !empty($key) && RateLimit::isBlocked($key);
		$remainingTime = $isBlocked ? RateLimit::getRemainingTime($key) : 0;

		$this->view('login', [
			'title' => 'Login',
			'isBlocked' => $isBlocked,
			'remainingTime' => $remainingTime
		]);
	}

	public function login() {
		if ($_SERVER["REQUEST_METHOD"] !== "POST") {
			header("Location: /");
			exit();
		}

		$username = htmlspecialchars($_POST['username']);
		$password = $_POST['password'];
		$key = "login_attempts_" . $username;

		// Cek apakah user masih dalam masa limit
		if (RateLimit::isBlocked($key)) {
			Session::set('error', "Terlalu banyak percobaan login. Silakan coba lagi nanti.");
			$_SESSION['last_username'] = $username; // Simpan username di session
			header("Location: /login");
			exit();
		}

		$userModel = new User();
		$user = $userModel->findByUsername($username);

		if ($user && password_verify($password, $user['password'])) {
			RateLimit::reset($key);
			unset($_SESSION['last_username']); // Hapus session jika berhasil login
			Session::set('user', ['id' => $user['id'], 'role' => $user['role']]);
			header("Location: " . ($user['role'] === 'admin' ? "/admin" : "/dashboard"));
			exit();
		} else {
			RateLimit::increment($key); // Tambah hitungan jika gagal login
			Session::set('error', "Username atau password salah!");
			$_SESSION['last_username'] = $username; // Simpan username yang gagal login
			header("Location: /login");
			exit();
		}
	}

	public function logout() {
		Session::destroy();
		header("Location: /");
		exit();
	}
}