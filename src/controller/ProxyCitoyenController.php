<?php

namespace App\Controller;

use App\Core\Abstract\AbstractController;

class ProxyCitoyenController extends AbstractController
{
    public function index() 
    {
        return $this->json(['message' => 'Proxy API Citoyen']);
    }

    public function create() 
    {
        return $this->json(['message' => 'Non implémenté']);
    }

    public function store() 
    {
        return $this->json(['message' => 'Non implémenté']);
    }

    public function show($id = null) 
    {
        // Récupérer le CNI depuis les paramètres GET
        $cni = $_GET['cni'] ?? $id;
        
        // Valider le CNI
        if (!$cni || strlen($cni) !== 13) {
            return $this->json([
                'data' => null,
                'statut' => 'error',
                'code' => 400,
                'message' => "Le numéro de carte d'identité doit contenir exactement 13 caractères"
            ], 400);
        }

        try {
            // Faire l'appel à votre API locale
            $url = "http://localhost:9000/api/v1/citoyens/{$cni}";
            
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => [
                        'Content-Type: application/json',
                        'Accept: application/json'
                    ],
                    'timeout' => 10
                ]
            ]);
            
            $response = file_get_contents($url, false, $context);
            
            if ($response === false) {
                return $this->json([
                    'data' => null,
                    'statut' => 'error',
                    'code' => 500,
                    'message' => "Erreur lors de l'appel à l'API externe"
                ], 500);
            }
            
            // Si votre API retourne déjà du JSON, décommentez cette ligne
            // $data = json_decode($response, true);
            
            // Pour l'instant, simulons les données basées sur le CNI
            $citoyens = [
                '1234567890123' => [
                    'nci' => '1234567890123',
                    'nom' => 'DIOP',
                    'prenom' => 'Amadou',
                    'date_naissance' => '1990-05-15',
                    'lieu_naissance' => 'Dakar',
                    'sexe' => 'M',
                    'adresse' => 'Médina, Dakar',
                    'date_emission' => '2020-01-15',
                    'date_expiration' => '2030-01-15'
                ],
                '9876543210987' => [
                    'nci' => '9876543210987',
                    'nom' => 'FALL',
                    'prenom' => 'Fatou',
                    'date_naissance' => '1985-12-20',
                    'lieu_naissance' => 'Saint-Louis',
                    'sexe' => 'F',
                    'adresse' => 'Plateau, Dakar',
                    'date_emission' => '2019-06-10',
                    'date_expiration' => '2029-06-10'
                ],
                '5555666677778' => [
                    'nci' => '5555666677778',
                    'nom' => 'SARR',
                    'prenom' => 'Ousmane',
                    'date_naissance' => '1988-03-08',
                    'lieu_naissance' => 'Thiès',
                    'sexe' => 'M',
                    'adresse' => 'Parcelles Assainies, Dakar',
                    'date_emission' => '2021-09-12',
                    'date_expiration' => '2031-09-12'
                ]
            ];
            
            $citoyen = $citoyens[$cni] ?? null;
            
            if ($citoyen) {
                return $this->json([
                    'data' => $citoyen,
                    'statut' => 'success',
                    'code' => 200,
                    'message' => "Le numéro de carte d'identité a été retrouvé"
                ]);
            } else {
                return $this->json([
                    'data' => null,
                    'statut' => 'error',
                    'code' => 404,
                    'message' => "Le numéro de carte d'identité non retrouvé"
                ], 404);
            }
            
        } catch (\Exception $e) {
            return $this->json([
                'data' => null,
                'statut' => 'error',
                'code' => 500,
                'message' => "Erreur lors de la récupération des données: " . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id) 
    {
        return $this->json(['message' => 'Non implémenté']);
    }

    public function destroy($id) 
    {
        return $this->json(['message' => 'Non implémenté']);
    }
}
