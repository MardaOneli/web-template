<?php
$autoload = __DIR__.'/../vendor/autoload.php';
require_once $autoload;

use Ramsey\Uuid\Uuid;

function guid() {
	return Uuid::uuid4()->toString();
}
?>