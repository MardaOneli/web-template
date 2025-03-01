<?php

class Security {
    public static function setHeaders() {
        // Mencegah Clickjacking dengan X-Frame-Options
        header("X-Frame-Options: DENY");

        // Mencegah MIME sniffing
        header("X-Content-Type-Options: nosniff");

        // Mengaktifkan proteksi terhadap XSS di beberapa browser
        header("X-XSS-Protection: 1; mode=block");

        // Content Security Policy (CSP) untuk mengontrol sumber daya eksternal
        header("Content-Security-Policy: default-src 'self'; script-src 'self'");

        // Referrer Policy untuk membatasi pengiriman referrer
        header("Referrer-Policy: no-referrer-when-downgrade");

        // Strict Transport Security (HSTS) untuk memastikan koneksi HTTPS
        header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
    }
}
