<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAXIT SA - Paiements</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .service-card {
            border-radius: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .service-card.active {
            border-color: #667eea;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
        }
        .woyofal-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            display: none;
        }
        .form-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        .btn-woyofal {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-woyofal:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .recu-card {
            background: #f8f9fa;
            border: 2px dashed #667eea;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1rem;
            display: none;
        }
        .coming-soon {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 3rem;
            text-align: center;
            display: none;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex">
        <!-- Sidebar - Identique au tableau de bord -->
        <div class="w-64 bg-gradient-to-b from-blue-600 to-blue-800 min-h-screen shadow-xl">
            <div class="p-6">
                <!-- Menu Items -->
                <div class="space-y-3">
                    <a href="/tableau-de-bord" class="block">
                        <div class="bg-blue-700 hover:bg-blue-600 rounded-lg p-4 cursor-pointer transition-all duration-200 transform hover:scale-105">
                            <span class="text-white font-medium">Tableau de bord</span>
                        </div>
                    </a>
                    <div class="bg-blue-500 rounded-lg p-4 cursor-pointer">
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

        <!-- Main Content - Contenu Paiement -->
        <div class="flex-1 p-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-credit-card text-blue-600"></i> Services de Paiement
                </h1>
                <p class="text-gray-600">Choisissez le service de paiement que vous souhaitez utiliser</p>
            </div>

            <!-- Informations du compte -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-8 mb-8 shadow-xl">
                <h2 class="text-2xl font-bold text-white mb-4">Solde disponible</h2>
                <div class="text-4xl font-bold text-white">
                    <?= number_format($solde, 0, ',', ' ') ?> <span class="text-xl">FCFA</span>
                </div>
                <p class="text-blue-200 mt-2">Compte Principal: <?= $compte->getNumero() ?></p>
            </div>

            <!-- Services disponibles -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="service-card bg-white p-6 rounded-2xl shadow-lg" onclick="selectService('woyofal')">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-bolt text-6xl text-yellow-500"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Woyofal</h3>
                        <p class="text-gray-600 mb-4">Crédit d'électricité SENELEC</p>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Disponible</span>
                    </div>
                </div>
                
                <div class="service-card bg-white p-6 rounded-2xl shadow-lg" onclick="selectService('internet')">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-wifi text-6xl text-blue-500"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Internet</h3>
                        <p class="text-gray-600 mb-4">Recharge internet & data</p>
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Bientôt</span>
                    </div>
                </div>
                
                <div class="service-card bg-white p-6 rounded-2xl shadow-lg" onclick="selectService('mobile')">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-mobile-alt text-6xl text-green-500"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Mobile</h3>
                        <p class="text-gray-600 mb-4">Recharge crédit téléphone</p>
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Bientôt</span>
                    </div>
                </div>
            </div>

            <!-- Section Woyofal -->
            <div id="woyofal-section" class="woyofal-section mb-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <h2 class="text-3xl font-bold text-white mb-6">
                            <i class="fas fa-bolt"></i> Woyofal - Achat de crédit électricité
                        </h2>
                        
                        <div class="form-card">
                            <form id="woyofalForm">
                                <div class="mb-6">
                                    <label for="numeroCompteur" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-hashtag text-blue-600"></i> Numéro de compteur SENELEC
                                    </label>
                                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           id="numeroCompteur" name="numero_compteur" 
                                           placeholder="Entrez le numéro de compteur" required>
                                    <p class="text-sm text-gray-500 mt-1">Le numéro de compteur SENELEC (ex: 123456789)</p>
                                </div>

                                <div class="mb-6">
                                    <label for="montant" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-money-bill-wave text-green-600"></i> Montant (FCFA)
                                    </label>
                                    <input type="number" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           id="montant" name="montant" 
                                           min="500" step="100" placeholder="Entrez le montant" required>
                                    <p class="text-sm text-gray-500 mt-1">Montant minimum : 500 FCFA</p>
                                </div>

                                <button type="submit" class="w-full btn-woyofal" id="btnAcheter">
                                    <span id="loadingSpinner" class="hidden">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                    <i class="fas fa-credit-card"></i> Acheter le crédit
                                </button>
                            </form>

                            <!-- Zone d'alerte -->
                            <div id="alertZone" class="mt-6"></div>

                            <!-- Reçu -->
                            <div id="recuCard" class="recu-card">
                                <h3 class="text-xl font-bold text-center text-blue-600 mb-4">
                                    <i class="fas fa-receipt"></i> Reçu d'achat
                                </h3>
                                <div id="recuContent"></div>
                                <div class="text-center mt-4">
                                    <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors" onclick="imprimerRecu()">
                                        <i class="fas fa-print"></i> Imprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <div class="bg-blue-600 text-white p-4 rounded-lg mb-4">
                                <h3 class="font-bold text-lg">
                                    <i class="fas fa-info-circle"></i> Informations Woyofal
                                </h3>
                            </div>
                            <ul class="space-y-3 text-sm">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Crédit instantané</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Code de recharge automatique</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Tarification par tranches</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Remise à zéro mensuelle</span>
                                </li>
                            </ul>
                            
                            <hr class="my-4">
                            
                            <h4 class="font-bold text-gray-800 mb-2">Tranches tarifaires:</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <div>• 0-150 kWh : 93 FCFA/kWh</div>
                                <div>• 151-250 kWh : 99 FCFA/kWh</div>
                                <div>• 251+ kWh : 105 FCFA/kWh</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section services à venir -->
            <div id="coming-soon-section" class="coming-soon">
                <div class="text-center">
                    <i class="fas fa-tools text-6xl text-gray-400 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Service en cours de développement</h3>
                    <p class="text-gray-600 mb-6">Ce service sera bientôt disponible. Revenez plus tard !</p>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors" onclick="selectService('woyofal')">
                        <i class="fas fa-bolt"></i> Utiliser Woyofal en attendant
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectService(service) {
            // Réinitialiser toutes les cartes
            document.querySelectorAll('.service-card').forEach(card => {
                card.classList.remove('active');
            });
            
            // Masquer toutes les sections
            document.getElementById('woyofal-section').style.display = 'none';
            document.getElementById('coming-soon-section').style.display = 'none';
            
            if (service === 'woyofal') {
                // Activer la carte Woyofal
                event.target.closest('.service-card').classList.add('active');
                // Afficher la section Woyofal
                document.getElementById('woyofal-section').style.display = 'block';
                document.getElementById('woyofal-section').scrollIntoView({ behavior: 'smooth' });
            } else {
                // Activer la carte sélectionnée
                event.target.closest('.service-card').classList.add('active');
                // Afficher "Coming soon"
                document.getElementById('coming-soon-section').style.display = 'block';
                document.getElementById('coming-soon-section').scrollIntoView({ behavior: 'smooth' });
            }
        }
        
        document.getElementById('woyofalForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = e.target;
            const btnAcheter = document.getElementById('btnAcheter');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const alertZone = document.getElementById('alertZone');
            
            // Désactiver le bouton et afficher le spinner
            btnAcheter.disabled = true;
            loadingSpinner.classList.remove('hidden');
            alertZone.innerHTML = '';
            
            const formData = {
                numero_compteur: form.numero_compteur.value,
                montant: parseFloat(form.montant.value)
            };
            
            try {
                const response = await fetch('/woyofal/acheter', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });
                
                const result = await response.json();
                
                if (result.statut === 'success') {
                    // Afficher le message de succès
                    alertZone.innerHTML = `
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                            <i class="fas fa-check-circle"></i> ${result.message}
                        </div>
                    `;
                    
                    // Afficher le reçu
                    afficherRecu(result.data);
                    
                    // Mettre à jour le solde
                    const soldeElement = document.querySelector('.text-4xl.font-bold.text-white');
                    if (soldeElement) {
                        soldeElement.innerHTML = new Intl.NumberFormat('fr-FR').format(result.data.nouveau_solde) + ' <span class="text-xl">FCFA</span>';
                    }
                    
                    // Réinitialiser le formulaire
                    form.reset();
                    
                } else {
                    // Afficher l'erreur
                    alertZone.innerHTML = `
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                            <i class="fas fa-exclamation-triangle"></i> ${result.message}
                        </div>
                    `;
                }
                
            } catch (error) {
                alertZone.innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-exclamation-triangle"></i> Erreur de connexion au service Woyofal
                    </div>
                `;
            } finally {
                // Réactiver le bouton et masquer le spinner
                btnAcheter.disabled = false;
                loadingSpinner.classList.add('hidden');
            }
        });
        
        function afficherRecu(data) {
            const recuContent = document.getElementById('recuContent');
            const recuCard = document.getElementById('recuCard');
            
            recuContent.innerHTML = `
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="font-semibold">Client :</div>
                    <div>${data.client}</div>
                    <div class="font-semibold">Compteur :</div>
                    <div>${data.compteur}</div>
                    <div class="font-semibold">Code de recharge :</div>
                    <div><code class="bg-blue-600 text-white px-2 py-1 rounded">${data.code}</code></div>
                    <div class="font-semibold">Kilowatts :</div>
                    <div>${data.nbreKwt} kWh</div>
                    <div class="font-semibold">Tranche :</div>
                    <div>${data.tranche}</div>
                    <div class="font-semibold">Prix unitaire :</div>
                    <div>${data.prix} FCFA/kWh</div>
                    <div class="font-semibold">Date :</div>
                    <div>${data.date}</div>
                    <div class="font-semibold">Référence :</div>
                    <div>${data.reference}</div>
                </div>
            `;
            
            recuCard.style.display = 'block';
            recuCard.scrollIntoView({ behavior: 'smooth' });
        }
        
        function imprimerRecu() {
            window.print();
        }
    </script>
</body>
</html>
