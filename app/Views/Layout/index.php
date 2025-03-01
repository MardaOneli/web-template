<main>
    <h2>Dashboard</h2>
    <p>Welcome, <?php echo isset($user['username']) ? $user['username'] : User; ?>!</p>
    <!-- Konten dashboard lainnya -->
    <a href="/logout">Logout</a>
</main>
