<?php
class RateLimit {
    private static $limit = 5;      // Batas maksimal percobaan login
    private static $timeout = 300;  // Waktu tunggu dalam detik (misalnya, 5 menit)

    // Metode untuk memeriksa apakah pengguna diblokir
    public static function isBlocked($key) {
        if (!isset($_SESSION['rate_limit'][$key])) {
            return false;
        }

        $attemptData = $_SESSION['rate_limit'][$key];
        if ($attemptData['count'] >= self::$limit) {
            $elapsedTime = time() - $attemptData['time'];
            return $elapsedTime < self::$timeout;
        }

        return false;
    }

    // Metode untuk mendapatkan sisa waktu blokir
    public static function getRemainingTime($key) {
        if (!isset($_SESSION['rate_limit'][$key])) {
            return 0;
        }

        $attemptData = $_SESSION['rate_limit'][$key];
        if ($attemptData['count'] >= self::$limit) {
            $elapsedTime = time() - $attemptData['time'];
            $remaining = self::$timeout - $elapsedTime;
            return max($remaining, 0);
        }

        return 0;
    }

    // Metode untuk menambahkan hitungan percobaan login
    public static function increment($key) {
        if (!isset($_SESSION['rate_limit'][$key])) {
            $_SESSION['rate_limit'][$key] = ['count' => 0, 'time' => time()];
        }

        $_SESSION['rate_limit'][$key]['count']++;
        if ($_SESSION['rate_limit'][$key]['count'] >= self::$limit) {
            $_SESSION['rate_limit'][$key]['time'] = time();
        }
    }

    // Metode untuk mereset hitungan percobaan login
    public static function reset($key) {
        unset($_SESSION['rate_limit'][$key]);
    }
}
