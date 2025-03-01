<?php
require_once '../core/Model.php';

class User extends Model {
	public function getUserById($id) {
		//var_dump($id); // Pastikan ID yang dikirim benar

		$stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
		$stmt->bindValue(':id', $id, PDO::PARAM_STR);
		$stmt->execute();

		// Debugging tambahan: cek error SQL
		if ($stmt->errorCode() !== '00000') {
			print_r($stmt->errorInfo());
		}

		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		return $user ?: null;
	}


	public function login($username, $password) {
		$stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
		$stmt->execute([$username]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($user && password_verify($password, $user['password'])) {
			return $user; // Login berhasil
		}
		return false;
	}
	public function register($id, $username, $password, $role) {
		$stmt = $this->db->prepare("INSERT INTO users (id, username, password, role) VALUES (?, ?, ?, ?)");
		if ($stmt->execute([$id, $username, $password, $role])) {
			return true;
		} else {
			return false;
		}
	}
	public function getRole($userId) {
		$stmt = $this->db->prepare("SELECT role FROM users WHERE id = ?");
		$stmt->execute([$userId]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		return $user ? $user['role'] : 'user'; // Default ke 'user' jika tidak ditemukan
	}
	public function findByUsername($username) {
		$stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
		$stmt->execute([$username]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
}