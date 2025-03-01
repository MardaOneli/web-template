<?php
class Controller {
    public function view($view, $data = []) {
        extract($data);
        ob_start();
        require_once "../app/Views/$view.php";
        $content = ob_get_clean();
        require_once "../app/Views/app.php";
    }
}
