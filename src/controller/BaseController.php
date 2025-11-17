<?php
class BaseController {
    protected function renderView($viewPath, $data = []) {
        extract($data);
        require_once $viewPath;
    }
    
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
}
?>