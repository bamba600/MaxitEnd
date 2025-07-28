<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;
use App\Entity\Utilisateur;
use App\Entity\Compte;
use App\Repository\UtilisateurRepository;
use App\Repository\CompteRepository;
use App\Repository\ProfilRepository;
use App\Service\UtilisateurService;
use App\Service\CompteService;
use App\Core\Validator;
use App\Core\FileUpload;

class ControllerCreerComptePrincipal extends AbstractController
{
    private UtilisateurService $utilisateurService;
    private CompteService $compteService;

    public function __construct()
    {
        parent::__construct();
        $this->utilisateurService = new UtilisateurService();
        $this->compteService = new CompteService();
    }

    public function index() 
    {
        $this->layout = null; // Désactiver le layout car creationCompte.php est un document complet
        $this->renderHtml('creationCompte.php');
    }

    public function create() 
    {
        $this->layout = null; // Désactiver le layout car creationCompte.php est un document complet
        $this->renderHtml('creationCompte.php');
    }

    public function store() 
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/creer-compte');
        }

        $errors = [];
        $data = $_POST;

        // Validation des données
        $errors = $this->validateData($data);

        if (!empty($errors)) {
            $this->renderHtml('creationCompte.php', ['errors' => $errors, 'data' => $data]);
            return;
        }

        // Gestion des uploads de fichiers
        $uploadResult = $this->handleFileUploads();
        if ($uploadResult['error']) {
            $errors['upload'] = $uploadResult['message'];
            $this->renderHtml('creationCompte.php', ['errors' => $errors, 'data' => $data]);
            return;
        }

        try {
            // Hasher le mot de passe après validation
            $hashedPassword = password_hash($data['mot_de_passe'], PASSWORD_BCRYPT);
            
            // Créer l'utilisateur avec le service
            $utilisateurData = [
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'login' => $data['login'],
                'mot_de_passe' => $hashedPassword,
                'telephone' => $data['numeroTelephone'],
                'numero_cni' => $data['numeroCNI'],
                'adresse' => $data['adresse'],
                'photo_recto' => $uploadResult['photorecto'],
                'photo_verso' => $uploadResult['photoverso'],
                'profil' => 'client'
            ];

            $utilisateur = $this->utilisateurService->createUtilisateur($utilisateurData);

            if (!$utilisateur) {
                $errors['general'] = 'Une erreur est survenue lors de la création de l\'utilisateur.';
                $this->renderHtml('creationCompte.php', ['errors' => $errors, 'data' => $data]);
                return;
            }

            // Créer le compte principal
            $compte = $this->compteService->createCompte($utilisateur->getId(), 'compte_principal', 0.0);

            if (!$compte) {
                $errors['general'] = 'Une erreur est survenue lors de la création du compte.';
                $this->renderHtml('creationCompte.php', ['errors' => $errors, 'data' => $data]);
                return;
            }

            // Rediriger vers la page de connexion après création réussie
            $this->session->set('creation_success', 'Votre compte a été créé avec succès. Veuillez vous connecter.');
            $this->redirect('/connexion');

        } catch (\Exception $e) {
            // Log l'erreur pour debug
            error_log("Erreur création compte: " . $e->getMessage() . " dans " . $e->getFile() . " ligne " . $e->getLine());
            error_log("Trace: " . $e->getTraceAsString());
            
            $errors['general'] = 'Une erreur est survenue lors de la création du compte: ' . $e->getMessage();
            $this->renderHtml('creationCompte.php', ['errors' => $errors, 'data' => $data]);
        }
    }

    private function validateData(array $data): array
    {
        $rules = [
            'nom' => ['required', 'name'],
            'prenom' => ['required', 'name'],
            'login' => [
                'required',
                ['min_length', 3],
                'login',
                ['unique_login', $this->utilisateurService]
            ],
            'mot_de_passe' => [
                'required',
                ['min_length', 6]
            ],
            'confirmer_mot_de_passe' => [
                'required'
            ],
            'numeroTelephone' => [
                'required',
                'phone_senegal',
                ['unique_phone', $this->utilisateurService]
            ],
            'numeroCNI' => [
                'required',
                'cni_senegal',
                ['unique_cni', $this->utilisateurService]
            ],
            'adresse' => ['required']
        ];

        // Validation avec la classe Validator
        Validator::validate($data, $rules);
        $errors = Validator::getErrors();

        // Validation confirmation mot de passe (avec nettoyage des espaces)
        $motDePasse = trim($data['mot_de_passe'] ?? '');
        $confirmerMotDePasse = trim($data['confirmer_mot_de_passe'] ?? '');
        
        if ($motDePasse !== $confirmerMotDePasse) {
            $errors['confirmer_mot_de_passe'][] = 'Les mots de passe ne correspondent pas';
        }

        // Validation fichiers
        if (empty($_FILES['photorecto']['name'])) {
            $errors['photorecto'][] = 'La photo recto de la CNI est requise';
        }

        if (empty($_FILES['photoverso']['name'])) {
            $errors['photoverso'][] = 'La photo verso de la CNI est requise';
        }

        return $errors;
    }

    private function handleFileUploads(): array
    {
        $fileUpload = new FileUpload();
        
        $result = ['error' => false, 'message' => '', 'photorecto' => '', 'photoverso' => ''];

        // Upload photo recto
        if (isset($_FILES['photorecto']) && $_FILES['photorecto']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $fileUpload->upload($_FILES['photorecto'], 'recto');
            
            if (!$uploadResult['success']) {
                return ['error' => true, 'message' => 'Photo recto: ' . $uploadResult['error']];
            }
            
            $result['photorecto'] = $uploadResult['filename'];
        }

        // Upload photo verso
        if (isset($_FILES['photoverso']) && $_FILES['photoverso']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $fileUpload->upload($_FILES['photoverso'], 'verso');
            
            if (!$uploadResult['success']) {
                // Supprimer le fichier recto si verso échoue
                if ($result['photorecto']) {
                    $fileUpload->delete($result['photorecto']);
                }
                return ['error' => true, 'message' => 'Photo verso: ' . $uploadResult['error']];
            }
            
            $result['photoverso'] = $uploadResult['filename'];
        }

        return $result;
    }

    private function generateUniqueNumeroCompte(): string
    {
        do {
            $numero = 'CPT' . date('Y') . sprintf('%06d', random_int(100000, 999999));
        } while ($this->compteService->existsByNumero($numero));
        
        return $numero;
    }

    public function getCitoyen() 
    {
        header('Content-Type: application/json');
        
        $cni = $_GET['cni'] ?? '';
        
        // Valider le CNI
        if (!$cni || strlen($cni) !== 13) {
            http_response_code(400);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 400,
                'message' => "Le numéro de carte d'identité doit contenir exactement 13 caractères"
            ]);
            exit;
        }

        try {
            // Appel à l'API externe
            $apiUrl = "https://application-daf.onrender.com/api/v1/citoyens/" . urlencode($cni);
            
            // Initialiser cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);
            
            // Vérifier les erreurs cURL
            if ($curlError) {
                http_response_code(500);
                echo json_encode([
                    'data' => null,
                    'statut' => 'error',
                    'code' => 500,
                    'message' => 'Erreur de connexion à l\'API externe'
                ]);
                exit;
            }
            
            // Décoder la réponse JSON
            $apiData = json_decode($response, true);
            
            if ($httpCode === 200 && $apiData) {
                // Succès - retourner les données de l'API
                echo json_encode([
                    'data' => $apiData['data'] ?? $apiData,
                    'statut' => 'success',
                    'code' => 200,
                    'message' => "Le numéro de carte d'identité a été retrouvé"
                ]);
            } elseif ($httpCode === 404) {
                // Citoyen non trouvé
                http_response_code(404);
                echo json_encode([
                    'data' => null,
                    'statut' => 'error',
                    'code' => 404,
                    'message' => "Le numéro de carte d'identité non retrouvé"
                ]);
            } else {
                // Autre erreur
                http_response_code($httpCode ?: 500);
                echo json_encode([
                    'data' => null,
                    'statut' => 'error',
                    'code' => $httpCode ?: 500,
                    'message' => $apiData['message'] ?? 'Erreur lors de la récupération des données'
                ]);
            }
            
        } catch (\Exception $e) {
            // Gestion des erreurs
            error_log("Erreur API getCitoyen: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 500,
                'message' => 'Une erreur interne est survenue'
            ]);
        }
        exit;
    }

    public function show($id = null) 
    {
        $this->redirect('/creer-compte');
    }

    public function edit($id) 
    {
        $this->redirect('/creer-compte');
    }

    public function update($id) 
    {
        // Non implémenté pour cette fonctionnalité
    }

    public function destroy($id) 
    {
        // Non implémenté pour cette fonctionnalité
    }
}
