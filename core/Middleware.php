<?php

require_once '../config/session.php';
require_once '../app/Models/User.php';

class Middleware {
    public static function auth() {
        if (!Session::has('user')) {
            header("Location: /login");
            exit();
        }
    }

    public static function guest() {
        if (Session::has('user')) {
            header("Location: /dashboard");
            exit();
        }
    }

    public static function role($allowedRoles = []) {
        if (!is_array($allowedRoles) || empty($allowedRoles)) {
            http_response_code(400);
            exit("Error: Parameter role harus berupa array dan tidak boleh kosong.");
        }

        if (!Session::has('user')) {
            header("Location: /login");
            exit();
        }

        $user = Session::get('user');
        if (!is_array($user) || !isset($user['id'])) {
            header("Location: /login");
            exit();
        }

        $userModel = new User();
        $userRole = $userModel->getRole($user['id']);

        if (!in_array($userRole, ['user', 'admin'], true)) {
            http_response_code(403);
            exit("Error: Role tidak valid.");
        }

        // Tentukan halaman redirect sesuai role
        $redirectPage = ($userRole === 'admin') ? "/admin" : "/dashboard";

        if (!in_array($userRole, $allowedRoles, true)) {
            header("HTTP/1.1 403 Forbidden");
            echo "Akses ditolak! Anda tidak memiliki izin.";
            
            // Redirect otomatis ke halaman sesuai role setelah 3 detik
            echo '<script>
                    setTimeout(function() {
                        window.location.href = "' . $redirectPage . '";
                    }, 3000);
                  </script>';
            exit();
        }
    }
}
