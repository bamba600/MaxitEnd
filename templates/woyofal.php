<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Woyofal - Achat de crédit électricité | MaxIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .woyofal-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
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
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
        .alert-custom {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/tableau-de-bord">
                <i class="fas fa-coins"></i> MaxIT
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/tableau-de-bord">
                    <i class="fas fa-tachometer-alt"></i> Tableau de bord
                </a>
                <a class="nav-link" href="/deconnexion">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- En-tête Woyofal -->
        <div class="woyofal-card text-center">
            <h1 class="mb-3">
                <i class="fas fa-bolt"></i> Woyofal
            </h1>
            <p class="lead mb-0">Système de prépaiement d'électricité SENELEC</p>
        </div>

        <div class="row">
            <div class="col-md-8">
                <!-- Formulaire d'achat -->
                <div class="form-card">
                    <h3 class="mb-4">
                        <i class="fas fa-shopping-cart"></i> Acheter un crédit électricité
                    </h3>

                    <form id="woyofalForm">
                        <div class="mb-3">
                            <label for="numeroCompteur" class="form-label">
                                <i class="fas fa-hashtag"></i> Numéro de compteur
                            </label>
                            <input type="text" class="form-control" id="numeroCompteur" name="numero_compteur" 
                                   placeholder="Entrez le numéro de compteur" required>
                            <div class="form-text">Le numéro de compteur SENELEC (ex: 123456789)</div>
                        </div>

                        <div class="mb-3">
                            <label for="montant" class="form-label">
                                <i class="fas fa-money-bill-wave"></i> Montant (FCFA)
                            </label>
                            <input type="number" class="form-control" id="montant" name="montant" 
                                   min="500" step="100" placeholder="Entrez le montant" required>
                            <div class="form-text">Montant minimum : 500 FCFA</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-woyofal" id="btnAcheter">
                                <span id="loadingSpinner" class="spinner-border spinner-border-sm me-2" style="display: none;"></span>
                                <i class="fas fa-credit-card"></i> Acheter le crédit
                            </button>
                        </div>
                    </form>

                    <!-- Zone d'alerte -->
                    <div id="alertZone" class="mt-3"></div>

                    <!-- Reçu -->
                    <div id="recuCard" class="recu-card">
                        <h5 class="text-center mb-3">
                            <i class="fas fa-receipt"></i> Reçu d'achat
                        </h5>
                        <div id="recuContent"></div>
                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-outline-primary" onclick="imprimerRecu()">
                                <i class="fas fa-print"></i> Imprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Informations du compte -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-wallet"></i> Votre compte
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <h4 class="text-primary" id="soldeActuel"><?= number_format($solde, 0, ',', ' ') ?> FCFA</h4>
                            <p class="text-muted">Solde disponible</p>
                        </div>
                        <hr>
                        <p><strong>Numéro :</strong> <?= $compte->getNumero() ?></p>
                        <p><strong>Type :</strong> Compte Principal</p>
                    </div>
                </div>

                <!-- Informations Woyofal -->
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle"></i> Informations
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled small">
                            <li><i class="fas fa-check text-success"></i> Crédit instantané</li>
                            <li><i class="fas fa-check text-success"></i> Code de recharge automatique</li>
                            <li><i class="fas fa-check text-success"></i> Tarification par tranches</li>
                            <li><i class="fas fa-check text-success"></i> Remise à zéro mensuelle</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('woyofalForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = e.target;
            const btnAcheter = document.getElementById('btnAcheter');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const alertZone = document.getElementById('alertZone');
            
            // Désactiver le bouton et afficher le spinner
            btnAcheter.disabled = true;
            loadingSpinner.style.display = 'inline-block';
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
                        <div class="alert alert-success alert-custom">
                            <i class="fas fa-check-circle"></i> ${result.message}
                        </div>
                    `;
                    
                    // Afficher le reçu
                    afficherRecu(result.data);
                    
                    // Mettre à jour le solde
                    document.getElementById('soldeActuel').textContent = 
                        new Intl.NumberFormat('fr-FR').format(result.data.nouveau_solde) + ' FCFA';
                    
                    // Réinitialiser le formulaire
                    form.reset();
                    
                } else {
                    // Afficher l'erreur
                    alertZone.innerHTML = `
                        <div class="alert alert-danger alert-custom">
                            <i class="fas fa-exclamation-triangle"></i> ${result.message}
                        </div>
                    `;
                }
                
            } catch (error) {
                alertZone.innerHTML = `
                    <div class="alert alert-danger alert-custom">
                        <i class="fas fa-exclamation-triangle"></i> Erreur de connexion au service Woyofal
                    </div>
                `;
            } finally {
                // Réactiver le bouton et masquer le spinner
                btnAcheter.disabled = false;
                loadingSpinner.style.display = 'none';
            }
        });
        
        function afficherRecu(data) {
            const recuContent = document.getElementById('recuContent');
            const recuCard = document.getElementById('recuCard');
            
            recuContent.innerHTML = `
                <div class="row">
                    <div class="col-6"><strong>Client :</strong></div>
                    <div class="col-6">${data.client}</div>
                </div>
                <div class="row">
                    <div class="col-6"><strong>Compteur :</strong></div>
                    <div class="col-6">${data.compteur}</div>
                </div>
                <div class="row">
                    <div class="col-6"><strong>Code de recharge :</strong></div>
                    <div class="col-6"><code class="bg-primary text-white p-1 rounded">${data.code}</code></div>
                </div>
                <div class="row">
                    <div class="col-6"><strong>Kilowatts :</strong></div>
                    <div class="col-6">${data.nbreKwt} kWh</div>
                </div>
                <div class="row">
                    <div class="col-6"><strong>Tranche :</strong></div>
                    <div class="col-6">${data.tranche}</div>
                </div>
                <div class="row">
                    <div class="col-6"><strong>Prix unitaire :</strong></div>
                    <div class="col-6">${data.prix} FCFA/kWh</div>
                </div>
                <div class="row">
                    <div class="col-6"><strong>Date :</strong></div>
                    <div class="col-6">${data.date}</div>
                </div>
                <div class="row">
                    <div class="col-6"><strong>Référence :</strong></div>
                    <div class="col-6">${data.reference}</div>
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
