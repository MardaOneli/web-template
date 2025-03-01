<?php

class ErrorHandler {
    public static function register() {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleException(Throwable $exception) {
        self::renderErrorPage($exception);
    }

    public static function handleError($errno, $errstr, $errfile, $errline) {
        if (!(error_reporting() & $errno)) {
            return;
        }
        self::renderErrorPage(new ErrorException($errstr, 0, $errno, $errfile, $errline));
    }

    public static function handleShutdown() {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::renderErrorPage(new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']));
        }
    }

    private static function renderErrorPage($exception) {
        //http_response_code(500);
        require_once '../app/views/error.php';
        exit();
    }
}

ErrorHandler::register();
