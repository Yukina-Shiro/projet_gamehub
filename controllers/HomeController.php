<?php
namespace mvcCore\Controllers;

use mvcCore\Controllers\Controller;
use mvcCore\Views\View;

class HomeController extends Controller {

    public function index() {
        echo "<h1>Bienvenue sur GameHub !</h1>";
        echo "<p>Ceci est la preuve que tout fonctionne.</p>";
    }

    // --- BLOC OBLIGATOIRE (Copie conforme du Parent) ---

    // Le parent dit : input( $method = INPUT_POST)
    public function input($method = INPUT_POST) {
        // Vide
    }

    // Le parent dit : create( $method = INPUT_POST, $redirect = 'read')
    public function create($method = INPUT_POST, $redirect = 'read') {
        // Vide
    }

    // Le parent dit : read( $method = INPUT_POST, $redirect = 'update')
    public function read($method = INPUT_POST, $redirect = 'update') {
        // Vide
    }

    // Le parent dit : update( $method = INPUT_POST, $redirect = 'read')
    public function update($method = INPUT_POST, $redirect = 'read') {
        // Vide
    }

    // Le parent dit : delete( $method = INPUT_POST, $redirect = 'create')
    public function delete($method = INPUT_POST, $redirect = 'create') {
        // Vide
    }
}