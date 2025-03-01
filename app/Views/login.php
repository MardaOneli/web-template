<div class="container d-flex justify-content-center">
    <div class="card mt-5 shadow-lg p-3">
        <div class="mt-3 text-center">
            <h2>Log in</h2>
        </div>
        <div class="card-body px-5">
            <?php if ($isBlocked): ?>
                <div class="alert alert-danger text-center">
                    Anda telah mencapai batas percobaan login. Silakan coba lagi dalam <b><?php echo $remainingTime; ?></b> detik.
                </div>
            <?php else: ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="px-5 btn btn-secondary">Log in</button>
                    </div>
                </form>
                <div class="text-center mt-2">
                    <a href="/register" class="btn text-muted text-decoration-none">Create Account</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
