<?php
require_once 'controllers/Controller.php';

class AdminController extends Controller {
    public function index() {
        // À REMPLIR : Vérifier si $_SESSION['role'] === 'admin'
        $this->render('admin/dashboard');
    }
}
?>