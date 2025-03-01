<?php
require_once 'config/database.php';
require_once 'includes/uuid.php';

function databaseExists($pdo, $dbname) {
    $stmt = $pdo->prepare("SHOW DATABASES LIKE ?");
    $stmt->execute([$dbname]);
    return $stmt->fetchColumn() ? true : false;
}

function createDatabase($pdo, $dbname) {
    if (!databaseExists($pdo, $dbname)) {
        $pdo->exec("CREATE DATABASE `$dbname`;");
        echo "Database '$dbname' berhasil dibuat.\n";
    } else {
        echo "Database '$dbname' sudah ada.\n";
    }
}

function tableExists($pdo, $table) {
    $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
    $stmt->execute([$table]);
    return $stmt->fetchColumn() ? true : false;
}

function createTables($pdo) {
    if (!tableExists($pdo, 'users')) {
        $pdo->exec("CREATE TABLE users (
            id VARCHAR(36) PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('user', 'admin') NOT NULL
        );");
        echo "Tabel 'users' berhasil dibuat.\n";
    } else {
        echo "Tabel 'users' sudah ada.\n";
    }
}

function dropDatabase($pdo, $dbname) {
    if (databaseExists($pdo, $dbname)) {
        $pdo->exec("DROP DATABASE `$dbname`;");
        echo "Database '$dbname' berhasil dihapus.\n";
    } else {
        echo "Database '$dbname' tidak ditemukan.\n";
    }
}

function addAdminUser($pdo, $username, $password) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $exists = $stmt->fetchColumn();
    $uuid = guid();

    if ($exists) {
        echo "Username '$username' sudah digunakan.\n";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (id, username, password, role) VALUES (?, ?, ?, 'admin')");
        $stmt->execute([$uuid, $username, $hashedPassword]);
        echo "Admin user '$username' berhasil ditambahkan.\n";
    }
}

function deleteUserByUsername($pdo, $username) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $exists = $stmt->fetchColumn();

    if (!$exists) {
        echo "User '$username' tidak ditemukan.\n";
    } else {
        $stmt = $pdo->prepare("DELETE FROM users WHERE username = ?");
        $stmt->execute([$username]);
        echo "User '$username' berhasil dihapus.\n";
    }
}

function listAdminUsers($pdo) {
    $stmt = $pdo->query("SELECT id, username FROM users WHERE role = 'admin'");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($admins)) {
        echo "Tidak ada user dengan role admin.\n";
    } else {
        echo "Daftar Admin:\n";
        echo str_repeat("-", 60) . "\n";
        echo sprintf("%-40s | %-15s\n", "ID", "Username");
        echo str_repeat("-", 60) . "\n";

        foreach ($admins as $admin) {
            echo sprintf("%-40s | %-15s\n", $admin['id'], $admin['username']);
        }

        echo str_repeat("-", 60) . "\n";
    }
}

try {
    // Koneksi awal tanpa database
    $pdo = new PDO(
        "mysql:host=" . DB_HOST,
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($argc < 2) {
        echo "Gunakan format:\n";
        echo "  Buat database : php generate_db.php setup\n";
        echo "  Hapus database : php generate_db.php drop\n";
        echo "  Tambah admin  : php generate_db.php add <username> <password>\n";
        echo "  Hapus user    : php generate_db.php delete <username>\n";
        echo "  List admin   : php generate_db.php list\n";
        exit(1);
    }

    $action = $argv[1];

    if ($action === "setup") {
        createDatabase($pdo, DB_NAME);
        $pdo->exec("USE " . DB_NAME . ";");
        createTables($pdo);
    } elseif ($action === "drop") {
        dropDatabase($pdo, DB_NAME);
    } else {
        // Koneksi ulang ke database karena perintah berikut butuh akses tabel
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($action === "add") {
            if ($argc !== 4) {
                echo "Gunakan format: php generate_db.php add <username> <password>\n";
                exit(1);
            }
            $username = $argv[2];
            $password = $argv[3];
            addAdminUser($pdo, $username, $password);
        } elseif ($action === "delete") {
            if ($argc !== 3) {
                echo "Gunakan format: php generate_db.php delete <username>\n";
                exit(1);
            }
            $username = $argv[2];
            deleteUserByUsername($pdo, $username);
        } elseif ($action === "list") {
            listAdminUsers($pdo);
        } else {
            echo "Perintah tidak dikenali. Gunakan 'setup', 'drop', 'add', 'delete', atau 'list'.\n";
            exit(1);
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
