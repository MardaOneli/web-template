<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title; ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
	<header>
		<?php
		if (isset($user)) {
			if ($user['role'] === 'admin') {
				require_once __DIR__ . '/Admin/header.php';
			} elseif ($user['role'] === 'user') {
				require_once __DIR__ . '/Layout/header.php';
			}
		}
		?>
	</header>
	<main>
		<?php echo $content; ?>
	</main>
	<footer>
		<?php
		if (isset($user)) {
			if ($user['role'] === 'admin') {
				require_once __DIR__ . '/Admin/footer.php';
			} elseif ($user['role'] === 'user') {
				require_once __DIR__ . '/Layout/footer.php';
			}
		}
		?>
	</footer>
</body>
</html>