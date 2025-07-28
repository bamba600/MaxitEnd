<?php

//route/route.web.php
use App\Core\Router;

// Définition des routes
Router::$routes = [
    // Route par défaut - redirection vers connexion
    "/" => [
        "controller" => "App\\Controller\\ControllerConnection",
        "action" => "index"
    ],
    
    // Routes de connexion
    "/connexion" => [
        "controller" => "App\\Controller\\ControllerConnection",
        "action" => "index"
    ],
    "/connexion/login" => [
        "controller" => "App\\Controller\\ControllerConnection",
        "action" => "login"
    ],
    "/deconnexion" => [
        "controller" => "App\\Controller\\ControllerConnection",
        "action" => "logout"
    ],
    
    // Routes de création de compte
    "/creer-compte" => [
        "controller" => "App\\Controller\\ControllerCreerComptePrincipal",
        "action" => "index"
    ],
    "/creer-compte/store" => [
        "controller" => "App\\Controller\\ControllerCreerComptePrincipal",
        "action" => "store"
    ],
    "/creer-compte/citoyen" => [
        "controller" => "App\\Controller\\ControllerCreerComptePrincipal",
        "action" => "getCitoyen"
    ],
    
    // Routes du tableau de bord
    "/tableau-de-bord" => [
        "controller" => "App\\Controller\\ControllerTableauDeBord",
        "action" => "index",
        "middleware" => ["auth"]
    ],
    
    // Routes Paiement
    "/paiement" => [
        "controller" => "App\\Controller\\PaiementController",
        "action" => "index",
        "middleware" => ["auth"]
    ],
    
    // Routes Woyofal
    "/woyofal" => [
        "controller" => "App\\Controller\\WoyofalController",
        "action" => "index",
        "middleware" => ["auth"]
    ],
    "/woyofal/acheter" => [
        "controller" => "App\\Controller\\WoyofalController",
        "action" => "acheter",
        "middleware" => ["auth"]
    ],
    "/woyofal/verifier" => [
        "controller" => "App\\Controller\\WoyofalController",
        "action" => "verifierCompteur",
        "middleware" => ["auth"]
    ],
    
    // Routes des transactions
    "/transactions" => [
        "controller" => "App\\Controller\\TransactionController",
        "action" => "index",
        "middleware" => ["auth"]
    ],
    
    // Routes des comptes
    "/comptes" => [
        "controller" => "App\\Controller\\CompteController",
        "action" => "index",
        "middleware" => ["auth"]
    ],
    
    // Route proxy pour les citoyens (évite les problèmes CORS)
    "/proxy/citoyens" => [
        "controller" => "App\\Controller\\ProxyCitoyenController",
        "action" => "show"
    ],
    
    // Route de test pour le formulaire
    "/test-form" => [
        "controller" => "App\\Controller\\TestFormController",
        "action" => "index"
    ]
];

// Le routeur sera appelé depuis index.php
