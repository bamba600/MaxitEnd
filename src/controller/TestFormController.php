<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;

class TestFormController extends AbstractController
{
    public function index()
    {
        // Afficher directement la page de test
        include '/home/bamba/Documents/Proget_MAxit4 (Copie)/public/test-app-form.php';
        exit;
    }
    
    public function create() {}
    public function store() {}
    public function show($id = null) {}
    public function edit($id) {}
    public function update($id) {}
    public function destroy($id) {}
}
