<?php
require_once '../core/Controller.php';
require_once '../config/session.php';
require_once '../core/Middleware.php';
require_once '../app/Models/User.php';

class PageController extends Controller {
	public function index() {
		// Pastikan hanya user yang login dapat mengakses dashboard
		Middleware::role(['user']);

		// Ambil data user dari session (biasanya hanya menyimpan id dan role)
		$sessionUser = $_SESSION['user'] ?? null;

		// Inisiasi model User
		$userModel = new User();

		// Ambil data lengkap user berdasarkan id
		$userData = $userModel->getUserById($sessionUser['id']);
		// var_dump($sessionUser['id']);
		// Render view dashboard melalui layout master (menggunakan layout jika diperlukan)
		$this->view('Layout/index', ['user' => $userData, 'title' => 'Dashboard']);
	}
}