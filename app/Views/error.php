<?php
$errorType = get_class($exception);
$errorMessage = $exception->getMessage();
$errorFile = $exception->getFile();
$errorLine = $exception->getLine();
$trace = $exception->getTraceAsString();
$headers = getallheaders();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oops! There is an error!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-danger text-center">
                <h1>⚠️ There is an error!</h1>
            </div>
            <div class="card-body">
                <p class="fw-bold">Type: <span class="text-danger"><?= $errorType ?></span></p>
                <p class="fw-bold">Message: <span class="text-danger"><?= $errorMessage ?></span></p>
                <p class="fw-bold">File: <span class="text-danger"><?= $errorFile ?></span></p>
                <p class="fw-bold">Line: <span class="text-danger"><?= $errorLine ?></span></p>
                <h3 class="mt-3">Trace:</h3>
                <pre class="p-3 rounded border border-secondary overflow-auto"><?= $trace ?></pre>
                <h3 class="mt-3">Request Headers:</h3>
                <pre class="p-3 rounded border border-secondary overflow-auto"><?php echo json_encode($headers, JSON_PRETTY_PRINT); ?></pre>
                <p class="mt-3 text-muted">Please double check your code or contact the developer.</p>
            </div>
        </div>
    </div>
</body>
</html>
