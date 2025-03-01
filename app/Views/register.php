<div class="container d-flex justify-content-center">
	<div class="card mt-5 shadow-lg p-3">
		<div class="mt-3 text-center">
			<h2>Register</h2>
		</div>
		<div class="card-body px-5">
			<?php if (isset($_SESSION['success'])): ?>
			<div class="alert alert-success">
				<?php
				echo $_SESSION['success'];
				unset($_SESSION['success']);
				?>
			</div>
			<?php endif; ?>

			<?php if (isset($_SESSION['error'])): ?>
			<div class="alert alert-danger">
				<?php
				echo $_SESSION['error'];
				unset($_SESSION['error']);
				?>
			</div>
			<?php endif; ?>

			<form method="POST" action="/register">
				<input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
				<div class="mb-3">
					<input type="text" class="form-control" name="username" placeholder="Username" required>
				</div>
				<div class="mb-3">
					<input type="password" class="form-control" name="password" placeholder="Password" required>
				</div>
				<div class="text-center">
					<button type="submit" class="px-5 btn btn-secondary">Register</button>
				</div>
			</form>
			<div class="text-center mt-2">
				<a href="/" class="btn text-muted text-decoration-none">Login</a>
			</div>
		</div>
	</div>
</div>