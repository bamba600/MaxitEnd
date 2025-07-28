<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Repository\CompteRepository;
use App\Service\TransactionService;

class WoyofalController extends AbstractController
{
    private CompteRepository $compteRepository;
    private TransactionService $transactionService;
    private string $woyofalApiUrl;

    public function __construct()
    {
        parent::__construct();
        $this->compteRepository = new CompteRepository();
        $this->transactionService = new TransactionService();
        $this->woyofalApiUrl = rtrim(getenv('AUTH_URL') ?: (\App\Config\Env::get('AUTH_URL', 'https://appwoyofal-qljz.onrender.com')), '/') . '/api/achat/acheter';
    }

    /**
     * Afficher la page d'achat Woyofal
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
            $this->session->set('error', 'Aucun compte principal trouvé.');
            $this->redirect('/tableau-de-bord');
        }

        $data = [
            'compte' => $comptePrincipal,
            'solde' => $comptePrincipal->getSolde()
        ];

        $this->renderHtml('woyofal.php', $data);
    }

    /**
     * Traiter l'achat de crédit Woyofal
     */
    public function acheter()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 405,
                'message' => 'Méthode non autorisée'
            ]);
            exit;
        }

        // Vérifier si l'utilisateur est connecté
        if (!$this->session->isset('user_id')) {
            http_response_code(401);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 401,
                'message' => 'Utilisateur non connecté'
            ]);
            exit;
        }

        $userId = $this->session->get('user_id');
        
        // Vérifier que le Content-Type est JSON
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') === false) {
            http_response_code(400);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 400,
                'message' => 'Content-Type doit être application/json'
            ]);
            exit;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Vérifier que le JSON est valide
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 400,
                'message' => 'JSON invalide: ' . json_last_error_msg()
            ]);
            exit;
        }

        // Validation des données
        $errors = [];
        $numeroCompteur = $input['numero_compteur'] ?? '';
        $montant = floatval($input['montant'] ?? 0);

        if (empty($numeroCompteur)) {
            $errors['numero'] = 'Le numéro de compteur est obligatoire';
        }

        if ($montant <= 0) {
            $errors['montant'] = 'Le montant doit être supérieur à 0';
        } elseif ($montant < 500) {
            $errors['montant'] = 'Le montant minimum est de 500 FCFA';
        }

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 400,
                'message' => 'Erreurs de validation',
                'errors' => $errors
            ]);
            exit;
        }

        try {
            // Récupérer le compte principal
            $comptePrincipal = $this->compteRepository->findComptePrincipalByUtilisateurId($userId);
            
            if (!$comptePrincipal) {
                http_response_code(404);
                echo json_encode([
                    'data' => null,
                    'statut' => 'error',
                    'code' => 404,
                    'message' => 'Compte principal non trouvé'
                ]);
                exit;
            }

            // Vérifier si le solde est suffisant
            if (!$this->transactionService->hasSufficientFunds($comptePrincipal->getId(), $montant)) {
                http_response_code(400);
                echo json_encode([
                    'data' => null,
                    'statut' => 'error',
                    'code' => 400,
                    'message' => 'Solde insuffisant'
                ]);
                exit;
            }

            // Appeler l'API Woyofal
            $woyofalResponse = $this->appelApiWoyofal($numeroCompteur, $montant);

            if ($woyofalResponse['statut'] === 'success') {
                // Effectuer le retrait du montant
                $retraitSuccess = $this->transactionService->withdraw(
                    $comptePrincipal->getId(),
                    $montant,
                    'Achat crédit Woyofal - Compteur: ' . $numeroCompteur
                );

                if ($retraitSuccess) {
                    // Journaliser l'achat
                    $this->journaliserAchat($userId, $numeroCompteur, $montant, $woyofalResponse, 'Success');

                    echo json_encode([
                        'data' => [
                            'compteur' => $woyofalResponse['data']['compteur'],
                            'reference' => $woyofalResponse['data']['reference'],
                            'code' => $woyofalResponse['data']['code'],
                            'date' => $woyofalResponse['data']['date'],
                            'tranche' => $woyofalResponse['data']['tranche'],
                            'prix' => $woyofalResponse['data']['prix'],
                            'nbreKwt' => $woyofalResponse['data']['nbreKwt'],
                            'client' => $woyofalResponse['data']['client'],
                            'nouveau_solde' => $comptePrincipal->getSolde() - $montant
                        ],
                        'statut' => 'success',
                        'code' => 200,
                        'message' => 'Achat effectué avec succès'
                    ]);
                } else {
                    throw new \Exception('Erreur lors du retrait du montant');
                }
            } else {
                // Journaliser l'échec
                $this->journaliserAchat($userId, $numeroCompteur, $montant, $woyofalResponse, 'Échec');
                
                // Retourner la réponse de l'API Woyofal avec le bon format
                http_response_code($woyofalResponse['code'] ?? 400);
                echo json_encode($woyofalResponse);
            }

        } catch (\Exception $e) {
            // Journaliser l'erreur
            $this->journaliserAchat($userId, $numeroCompteur, $montant, null, 'Erreur');
            
            error_log("Erreur achat Woyofal: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 500,
                'message' => 'Erreur interne du serveur'
            ]);
        }
        exit;
    }

    /**
     * Appeler l'API Woyofal
     */
    private function appelApiWoyofal(string $numeroCompteur, float $montant): array
    {
        $data = [
            'numero_compteur' => $numeroCompteur,
            'montant' => $montant
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->woyofalApiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new \Exception('Erreur de connexion à l\'API Woyofal: ' . $curlError);
        }

        $responseData = json_decode($response, true);
        
        if (!$responseData) {
            throw new \Exception('Réponse invalide de l\'API Woyofal');
        }

        return $responseData;
    }

    /**
     * Journaliser l'achat selon les critères d'acceptation
     * Toutes les demandes d'achat (date,heure,localisation,@Ip,statut[Success|Échec],numero compteur, code recharge,nombre KWT)
     */
    private function journaliserAchat(int $userId, string $numeroCompteur, float $montant, ?array $response, string $statut): void
    {
        // Log simple dans le fichier système pour debug uniquement
        $logMessage = sprintf(
            "[%s] Woyofal %s - User:%d Compteur:%s Montant:%s", 
            date('Y-m-d H:i:s'), 
            $statut, 
            $userId, 
            $numeroCompteur, 
            $montant
        );
        
        error_log($logMessage);
    }
    
    /**
     * Obtenir la localisation approximative du client (basic)
     */
    private function getClientLocation(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        return ($ip === '127.0.0.1' || $ip === '::1') ? 'Local' : "IP: {$ip}";
    }

    public function create() 
    {
        $this->redirect('/woyofal');
    }

    public function store() 
    {
        $this->redirect('/woyofal');
    }

    public function show($id = null) 
    {
        $this->redirect('/woyofal');
    }

    public function edit($id) 
    {
        $this->redirect('/woyofal');
    }

    public function update($id) 
    {
        // Non implémenté
    }

    public function destroy($id) 
    {
        // Non implémenté
    }

    /**
     * Vérifier l'existence d'un numéro de compteur
     * Endpoint AJAX pour validation en temps réel
     */
    public function verifierCompteur()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 405,
                'message' => 'Méthode non autorisée'
            ]);
            exit;
        }

        // Vérifier si l'utilisateur est connecté
        if (!$this->session->isset('user_id')) {
            http_response_code(401);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 401,
                'message' => 'Utilisateur non connecté'
            ]);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $numeroCompteur = $input['numero_compteur'] ?? '';

        if (empty($numeroCompteur)) {
            http_response_code(400);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 400,
                'message' => 'Le numéro de compteur est obligatoire'
            ]);
            exit;
        }

        try {
            // Appeler l'API Woyofal pour vérifier le compteur
            // On utilise un montant minimal, l'API nous dira si le compteur est valide
            $verificationResponse = $this->appelApiWoyofalVerification($numeroCompteur);

            if ($verificationResponse['statut'] === 'success') {
                // Le compteur est valide, extraire les infos client
                echo json_encode([
                    'data' => [
                        'compteur' => $numeroCompteur,
                        'client' => $verificationResponse['data']['client'] ?? 'Client Senelec',
                        'valide' => true
                    ],
                    'statut' => 'success',
                    'code' => 200,
                    'message' => 'Numéro de compteur valide'
                ]);
            } else {
                // Le compteur n'est pas valide, retourner l'erreur de l'API
                http_response_code($verificationResponse['code'] ?? 404);
                echo json_encode([
                    'data' => [
                        'compteur' => $numeroCompteur,
                        'valide' => false
                    ],
                    'statut' => 'error',
                    'code' => $verificationResponse['code'] ?? 404,
                    'message' => $verificationResponse['message'] ?? 'Numéro de compteur non trouvé'
                ]);
            }

        } catch (\Exception $e) {
            error_log("Erreur vérification compteur Woyofal: " . $e->getMessage());
            
            // Différencier les erreurs de connexion des erreurs de validation
            if (strpos($e->getMessage(), 'connexion') !== false) {
                http_response_code(503);
                echo json_encode([
                    'data' => null,
                    'statut' => 'error',
                    'code' => 503,
                    'message' => 'Service Woyofal temporairement indisponible'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'data' => null,
                    'statut' => 'error',
                    'code' => 500,
                    'message' => 'Erreur lors de la vérification'
                ]);
            }
        }
        exit;
    }

    /**
     * Appeler l'API Woyofal pour vérification uniquement
     * L'API AppWoyofal détermine elle-même si le compteur est valide
     */
    private function appelApiWoyofalVerification(string $numeroCompteur): array
    {
        // Utiliser un montant minimal pour tester l'existence du compteur
        // L'API AppWoyofal nous dira si le compteur existe ou non
        $data = [
            'numero_compteur' => $numeroCompteur,
            'montant' => 500 // Montant minimal pour test de validation
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->woyofalApiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new \Exception('Erreur de connexion à l\'API Woyofal: ' . $curlError);
        }

        // Vérifier si l'API est disponible (éviter les erreurs 404 HTML)
        if ($httpCode === 404 && (strpos($response, 'html') !== false || strpos($response, 'Not Found') !== false)) {
            throw new \Exception('API Woyofal non disponible (Service non démarré sur le port 8000)');
        }

        $responseData = json_decode($response, true);
        
        if (!$responseData) {
            throw new \Exception('Réponse invalide de l\'API Woyofal (HTTP ' . $httpCode . ')');
        }

        return $responseData;
    }
}
