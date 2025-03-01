<main>
    <h2>Dashboard</h2>
    <p>Halo, <?php echo isset($user['username']) ? $user['username'] : Admin; ?>! Anda memiliki hak akses penuh.</p>
    <!-- Konten dashboard lainnya -->
    <a href="/logout">Logout</a>
</main>
