<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Repository\CompteRepository;

class PaiementController extends AbstractController
{
    private CompteRepository $compteRepository;

    public function __construct()
    {
        parent::__construct();
        $this->compteRepository = new CompteRepository();
    }

    /**
     * Afficher la page des paiements avec les différents services
     */
    public function index()
    {
        // Vérifier si l'utilisateur est connecté
        if (!$this->session->isset('user_id')) {
            $this->redirect('/connexion');
        }

        $userId = $this->session->get('user_id');
        
        // Récupérer le compte principal
        $comptePrincipal = $this->compteRepository->findComptePrincipalByUtilisateurId($userId);
        
        if (!$comptePrincipal) {
            // Si c'est une requête AJAX, retourner un message d'erreur au lieu de rediriger
            if (isset($_GET['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')) {
                echo '<div class="text-center py-8">
                    <div class="text-red-600 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.081 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 mb-4">Aucun compte principal trouvé. Veuillez créer un compte d\'abord.</p>
                    <button onclick="chargerTableauDeBord()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Retour au tableau de bord</button>
                </div>';
                return;
            }
            
            $this->session->set('error', 'Aucun compte principal trouvé.');
            $this->redirect('/tableau-de-bord');
        }

        $data = [
            'compte' => $comptePrincipal,
            'solde' => $comptePrincipal->getSolde()
        ];

        // Si c'est une requête AJAX (détectée par un header ou un paramètre), renvoyer seulement le contenu
        if (isset($_GET['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')) {
            // Désactiver le layout pour retourner uniquement le contenu
            $this->layout = null;
            $this->renderHtml('paiement-content.php', $data);
        } else {
            $this->renderHtml('paiement.php', $data);
        }
    }

    public function create() 
    {
        $this->redirect('/paiement');
    }

    public function store() 
    {
        $this->redirect('/paiement');
    }

    public function show($id = null) 
    {
        $this->redirect('/paiement');
    }

    public function edit($id) 
    {
        $this->redirect('/paiement');
    }

    public function update($id) 
    {
        // Non implémenté
    }

    public function destroy($id) 
    {
        // Non implémenté
    }
}
