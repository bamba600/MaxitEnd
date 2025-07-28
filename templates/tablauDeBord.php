<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAXIT SA - Tableau de Bord</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gradient-to-b from-blue-600 to-blue-800 min-h-screen shadow-xl">
            <div class="p-6">
                <!-- Menu Items -->
                <div class="space-y-3">
                    <div class="bg-blue-500 rounded-lg p-4 cursor-pointer" id="btn-dashboard" onclick="chargerTableauDeBord()">
                        <span class="text-white font-medium">Tableau de bord</span>
                    </div>
                    <div class="bg-blue-700 hover:bg-blue-600 rounded-lg p-4 cursor-pointer transition-all duration-200 transform hover:scale-105" id="btn-paiement" onclick="console.log('Bouton paiement cliqué!'); chargerPaiement(); return false;">
                        <span class="text-white font-medium">💳 Paiement</span>
                    </div>
                    <div class="bg-blue-700 hover:bg-blue-600 rounded-lg p-4 cursor-pointer transition-all duration-200 transform hover:scale-105">
                        <span class="text-white font-medium">Transfert</span>
                    </div>
                    <div class="bg-blue-700 hover:bg-blue-600 rounded-lg p-4 cursor-pointer transition-all duration-200 transform hover:scale-105">
                        <span class="text-white font-medium">Comptes</span>
                    </div>
                    <a href="/deconnexion" class="block mt-8">
                        <div class="bg-red-600 hover:bg-red-700 rounded-lg p-4 cursor-pointer transition-all duration-200 transform hover:scale-105">
                            <span class="text-white font-medium">Déconnexion</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8" id="main-content">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    Bonjour <?= htmlspecialchars($utilisateur->getNomComplet() ?? 'Utilisateur') ?>
                </h1>
            </div>

            <!-- Compte Principal Card -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-8 mb-8 shadow-xl">
                <h2 class="text-2xl font-bold text-white mb-4">Compte Principal</h2>
                <div class="text-4xl font-bold text-white">
                    <?= number_format($comptePrincipal ? $comptePrincipal->getSolde() : 0, 2, ',', ' ') ?> <span class="text-xl">FCFA</span>
                </div>
            </div>

            <!-- Transactions Section -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">10 dernières transactions</h3>
                
                <?php if (empty($dernieresTransactions)): ?>
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-600 text-lg">Aucune transaction pour le moment</p>
                        <p class="text-gray-500 text-sm">Vos transactions apparaîtront ici</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($dernieresTransactions as $transaction): ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <?php
                                    $isCredit = in_array($transaction->getType(), ['depot']);
                                    $iconColor = $isCredit ? 'green' : 'red';
                                    ?>
                                    <div class="w-12 h-12 bg-<?= $iconColor ?>-100 rounded-full flex items-center justify-center">
                                        <?php if ($isCredit): ?>
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        <?php else: ?>
                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-500"><?= $transaction->getDate()->format('d/m/Y') ?></span>
                                            <span class="text-gray-800 font-medium"><?= htmlspecialchars($transaction->getDescription() ?: ucfirst($transaction->getType())) ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-<?= $iconColor ?>-600 font-semibold">
                                    <?= ($isCredit ? '+' : '-') . number_format($transaction->getMontant(), 0, ',', ' ') ?> FCFA
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Voir toutes les transactions -->
            <div class="text-center">
                <a href="#" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <span>Voir toutes les transactions</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <script>
        let currentView = 'dashboard'; // État actuel de la vue

        // Contenu original du tableau de bord
        const dashboardContent = document.getElementById('main-content').innerHTML;

        // Fonction pour charger le contenu des paiements
        function chargerPaiement() {
            console.log('chargerPaiement() appelée');
            const mainContent = document.getElementById('main-content');
            
            // Afficher un loader
            mainContent.innerHTML = `
                <div class="flex items-center justify-center h-64">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                    <span class="ml-3 text-gray-600">Chargement...</span>
                </div>
            `;

            console.log('Envoi de la requête AJAX vers /paiement?ajax=1');
            
            // Charger le contenu des paiements via AJAX avec le paramètre ajax
            fetch('/paiement?ajax=1', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    console.log('Réponse reçue:', response.status, response.statusText);
                    return response.text();
                })
                .then(html => {
                    console.log('HTML reçu (premiers 200 caractères):', html.substring(0, 200));
                    mainContent.innerHTML = html;
                    
                    // Exécuter les scripts contenus dans le HTML injecté
                    const scripts = mainContent.querySelectorAll('script');
                    console.log('Scripts trouvés dans le contenu AJAX:', scripts.length);
                    
                    scripts.forEach((script, index) => {
                        console.log(`Exécution du script ${index + 1}/${scripts.length}`);
                        try {
                            if (script.src) {
                                // Script externe
                                const newScript = document.createElement('script');
                                newScript.src = script.src;
                                newScript.onload = () => console.log(`Script externe ${script.src} chargé`);
                                document.head.appendChild(newScript);
                            } else {
                                // Script inline - utiliser eval pour forcer l'exécution
                                console.log('Exécution script inline avec eval...');
                                eval(script.textContent);
                            }
                            console.log(`Script ${index + 1} exécuté avec succès`);
                        } catch (error) {
                            console.error(`Erreur lors de l'exécution du script ${index + 1}:`, error);
                        }
                    });
                    
                    currentView = 'paiement';
                    updateNavigation();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    mainContent.innerHTML = `
                        <div class="text-center py-8">
                            <div class="text-red-600 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.081 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-600">Erreur lors du chargement des paiements</p>
                            <button onclick="chargerPaiement()" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Réessayer</button>
                        </div>
                    `;
                });
        }

        // Fonction pour revenir au tableau de bord
        function chargerTableauDeBord() {
            if (currentView !== 'dashboard') {
                document.getElementById('main-content').innerHTML = dashboardContent;
                currentView = 'dashboard';
                updateNavigation();
            }
        }

        // Fonction pour mettre à jour la navigation
        function updateNavigation() {
            const btnDashboard = document.getElementById('btn-dashboard');
            const btnPaiement = document.getElementById('btn-paiement');
            
            // Réinitialiser les styles
            btnDashboard.className = 'bg-blue-700 hover:bg-blue-600 rounded-lg p-4 cursor-pointer transition-all duration-200 transform hover:scale-105';
            btnPaiement.className = 'bg-blue-700 hover:bg-blue-600 rounded-lg p-4 cursor-pointer transition-all duration-200 transform hover:scale-105';
            
            // Mettre en évidence le bouton actuel
            if (currentView === 'dashboard') {
                btnDashboard.className = 'bg-blue-500 rounded-lg p-4 cursor-pointer';
            } else if (currentView === 'paiement') {
                btnPaiement.className = 'bg-blue-500 rounded-lg p-4 cursor-pointer';
            }
        }
    </script>
</body>
</html>