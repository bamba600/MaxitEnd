<!-- Tailwind CSS pour le style autonome -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Font Awesome pour les icônes -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<!-- Styles CSS intégrés -->
<style>
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
}
.form-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    padding: 2rem;
}
.coming-soon {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 3rem;
    text-align: center;
}

/* CORRECTION CRITIQUE: Styles pour résoudre le problème de saisie */
input[type="text"], 
input[type="number"], 
input[type="email"], 
input[type="password"] {
    /* Reset complet des styles d'input */
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    
    /* Propriétés essentielles pour la saisie */
    pointer-events: auto !important;
    user-select: text !important;
    -webkit-user-select: text !important;
    -moz-user-select: text !important;
    
    /* Affichage et interaction */
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    
    /* Couleurs et arrière-plan */
    background-color: white !important;
    background: white !important;
    color: #111827 !important;
    
    /* Reset de toutes les propriétés pouvant bloquer */
    z-index: auto !important;
    position: relative !important;
    overflow: visible !important;
    clip: auto !important;
    clip-path: none !important;
    transform: none !important;
    filter: none !important;
    
    /* Interaction */
    touch-action: manipulation !important;
    cursor: text !important;
}

/* États focus et hover */
input[type="text"]:focus, 
input[type="number"]:focus {
    outline: 2px solid #3b82f6 !important;
    outline-offset: 2px !important;
    border-color: #3b82f6 !important;
}

/* Messages d'erreur */
.error-message {
    color: #dc2626;
    font-size: 14px;
    margin-top: 4px;
}

.error-message.hidden {
    display: none;
}

/* Styles pour le reçu */
#receiptModal {
    animation: fadeIn 0.3s ease-out;
}

#receiptModal .bg-white {
    animation: slideIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Styles d'impression pour le reçu */
@media print {
    #receiptModal {
        position: static !important;
        background: transparent !important;
    }
    
    .no-print, button {
        display: none !important;
    }
}
</style>

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
    <div class="service-card bg-white p-6 rounded-2xl shadow-lg cursor-pointer transition-all duration-300 hover:transform hover:translate-y-[-5px] hover:shadow-xl" data-service="woyofal">
        <div class="text-center">
            <div class="mb-4">
                <i class="fas fa-bolt text-6xl text-yellow-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Woyofal</h3>
            <p class="text-gray-600 mb-4">Crédit d'électricité SENELEC</p>
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Disponible</span>
        </div>
    </div>
    
    <div class="service-card bg-white p-6 rounded-2xl shadow-lg cursor-pointer transition-all duration-300 hover:transform hover:translate-y-[-5px] hover:shadow-xl" data-service="internet">
        <div class="text-center">
            <div class="mb-4">
                <i class="fas fa-wifi text-6xl text-blue-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Internet</h3>
            <p class="text-gray-600 mb-4">Recharge internet & data</p>
            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Bientôt</span>
        </div>
    </div>
    
    <div class="service-card bg-white p-6 rounded-2xl shadow-lg cursor-pointer transition-all duration-300 hover:transform hover:translate-y-[-5px] hover:shadow-xl" data-service="mobile">
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
<div id="woyofal-section" class="woyofal-section mb-8" style="display: none;">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <h2 class="text-3xl font-bold text-white mb-6">
                <i class="fas fa-bolt"></i> Woyofal - Achat de crédit électricité
            </h2>
            
            <div class="form-card bg-white rounded-2xl shadow-lg p-6">
                <form id="woyofalForm">
                    <div class="mb-6">
                        <label for="numeroCompteur" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-hashtag text-blue-600"></i> Numéro de compteur SENELEC
                        </label>
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               id="numeroCompteur" name="numero_compteur" 
                               placeholder="Entrez le numéro de compteur" required>
                        <div id="error-numero" class="text-red-600 text-sm mt-1 hidden"></div>
                        <div id="success-numero" class="text-green-600 text-sm mt-1 hidden"></div>
                        <div id="loading-numero" class="text-blue-600 text-sm mt-1 hidden">
                            <i class="fas fa-spinner fa-spin"></i> Vérification en cours...
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Le numéro de compteur SENELEC (ex: 123456789)</p>
                    </div>

                    <div class="mb-6">
                        <label for="montant" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-money-bill-wave text-green-600"></i> Montant (FCFA)
                        </label>
                        <input type="number" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               id="montant" name="montant" 
                               min="500" step="100" placeholder="Entrez le montant" required>
                        <div id="error-montant" class="text-red-600 text-sm mt-1 hidden"></div>
                        <p class="text-sm text-gray-500 mt-1">Montant minimum : 500 FCFA</p>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105" id="btnAcheter">
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
            </div>
        </div>
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-info-circle text-blue-600"></i> Informations
                </h3>
                <div class="space-y-4 text-sm text-gray-600">
                    <div>
                        <strong>• Commission :</strong> Aucune
                    </div>
                    <div>
                        <strong>• Traitement :</strong> Instantané
                    </div>
                    <div>
                        <strong>• Disponibilité :</strong> 24h/24
                    </div>
                    <div>
                        <strong>• Reçu :</strong> Envoyé par SMS
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section Coming Soon pour autres services -->
<div id="internet-section" class="coming-soon mb-8" style="display: none;">
    <i class="fas fa-wifi text-6xl text-blue-500 mb-4"></i>
    <h3 class="text-2xl font-bold text-gray-800 mb-2">Service Internet</h3>
    <p class="text-gray-600 mb-4">Ce service sera bientôt disponible</p>
    <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg inline-block">
        <i class="fas fa-clock"></i> En développement
    </div>
</div>

<div id="mobile-section" class="coming-soon mb-8" style="display: none;">
    <i class="fas fa-mobile-alt text-6xl text-green-500 mb-4"></i>
    <h3 class="text-2xl font-bold text-gray-800 mb-2">Service Mobile</h3>
    <p class="text-gray-600 mb-4">Ce service sera bientôt disponible</p>
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg inline-block">
        <i class="fas fa-clock"></i> En développement
    </div>
</div>

<script>
console.log('=== DEBUT SCRIPT PAIEMENT ===');

// Définir toutes les fonctions dans le contexte global
window.selectService = function(service) {
    console.log('selectService appelée avec:', service);
    
    try {
        // Masquer toutes les sections
        const sections = ['woyofal-section', 'internet-section', 'mobile-section'];
        sections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            if (section) {
                section.style.display = 'none';
                console.log('Section ' + sectionId + ' masquée');
            } else {
                console.warn(`Section ${sectionId} non trouvée`);
            }
        });
        
        // Retirer la classe active de toutes les cartes
        document.querySelectorAll('.service-card').forEach(card => {
            card.classList.remove('active');
        });
        
        // Afficher la section sélectionnée
        const targetSection = document.getElementById(service + '-section');
        if (targetSection) {
            console.log(`Affichage de la section ${service}`);
            targetSection.style.display = 'block';
            
            // Activer la carte correspondante
            const targetCard = document.querySelector(`[data-service="${service}"]`);
            if (targetCard) {
                targetCard.classList.add('active');
                console.log(`Carte ${service} activée`);
            }
        } else {
            console.error(`Section ${service}-section non trouvée`);
        }
    } catch (error) {
        console.error('Erreur dans selectService:', error);
    }
};

window.showAlert = function(message, type) {
    try {
        const alertZone = document.getElementById('alertZone');
        if (!alertZone) {
            console.error('Element alertZone non trouvé!');
            return;
        }
        
        const alertClass = type === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700';
        
        alertZone.innerHTML = `
            <div class="${alertClass} border-l-4 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm">${message}</p>
                    </div>
                </div>
            </div>
        `;
        
        setTimeout(() => {
            if (alertZone) alertZone.innerHTML = '';
        }, 5000);
    } catch (error) {
        console.error('Erreur dans showAlert:', error);
    }
};

// Fonctions pour gérer les erreurs des champs
window.showFieldError = function(fieldName, message) {
    console.log('showFieldError appelée:', fieldName, message);
    const errorElement = document.getElementById('error-' + fieldName);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
        errorElement.style.display = 'block';
        console.log('Erreur affichée pour', fieldName, ':', message);
    } else {
        console.error('Element error-' + fieldName + ' non trouvé!');
    }
};

window.hideFieldError = function(fieldName) {
    const errorElement = document.getElementById('error-' + fieldName);
    if (errorElement) {
        errorElement.textContent = '';
        errorElement.classList.add('hidden');
        errorElement.style.display = 'none';
    }
};

// Fonction pour afficher le succès sous un champ
window.showFieldSuccess = function(fieldName, message) {
    const successElement = document.getElementById('success-' + fieldName);
    if (successElement) {
        successElement.textContent = message;
        successElement.classList.remove('hidden');
        successElement.style.display = 'block';
    }
};

window.hideFieldSuccess = function(fieldName) {
    const successElement = document.getElementById('success-' + fieldName);
    if (successElement) {
        successElement.textContent = '';
        successElement.classList.add('hidden');
        successElement.style.display = 'none';
    }
};

// Fonction pour afficher/masquer le loading
window.showFieldLoading = function(fieldName, show = true) {
    const loadingElement = document.getElementById('loading-' + fieldName);
    if (loadingElement) {
        if (show) {
            loadingElement.classList.remove('hidden');
            loadingElement.style.display = 'block';
        } else {
            loadingElement.classList.add('hidden');
            loadingElement.style.display = 'none';
        }
    }
};

// Fonction pour vérifier le numéro de compteur en temps réel
window.verifierNumeroCompteur = function(numeroCompteur) {
    if (!numeroCompteur || numeroCompteur.length < 3) {
        hideFieldError('numero');
        hideFieldSuccess('numero');
        showFieldLoading('numero', false);
        return;
    }
    
    // Afficher le loading
    hideFieldError('numero');
    hideFieldSuccess('numero');
    showFieldLoading('numero', true);
    
    // Appel AJAX vers l'API de vérification
    fetch('/woyofal/verifier', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            numero_compteur: numeroCompteur
        })
    })
    .then(response => response.json())
    .then(data => {
        showFieldLoading('numero', false);
        
        if (data.statut === 'success') {
            showFieldSuccess('numero', `✓ Compteur valide - Client: ${data.data.client}`);
            hideFieldError('numero');
        } else {
            showFieldError('numero', data.message || 'Numéro de compteur non trouvé');
            hideFieldSuccess('numero');
        }
    })
    .catch(error => {
        console.error('Erreur vérification compteur:', error);
        showFieldLoading('numero', false);
        showFieldError('numero', 'Service Woyofal temporairement indisponible');
        hideFieldSuccess('numero');
    });
};

// Fonction pour forcer l'activation des champs de saisie
window.forceInputActivation = function() {
    console.log('🔧 Activation forcée des champs de saisie...');
    
    const inputs = document.querySelectorAll('input[type="text"], input[type="number"]');
    inputs.forEach((input, index) => {
        console.log(`Activation du champ ${index + 1}:`, input.id || input.name);
        
        // Supprimer tous les attributs qui pourraient bloquer
        input.removeAttribute('disabled');
        input.removeAttribute('readonly');
        input.style.pointerEvents = 'auto';
        input.style.userSelect = 'text';
        input.style.cursor = 'text';
        input.style.backgroundColor = 'white';
        input.style.color = 'black';
        input.style.opacity = '1';
        input.style.visibility = 'visible';
        input.style.display = 'block';
        
        // Forcer les propriétés CSS critiques
        input.style.setProperty('pointer-events', 'auto', 'important');
        input.style.setProperty('user-select', 'text', 'important');
        input.style.setProperty('background-color', 'white', 'important');
        input.style.setProperty('color', '#111827', 'important');
        
        // Assurer que le tabIndex est correct
        if (input.tabIndex < 0) {
            input.tabIndex = 0;
        }
    });
};

window.showReceipt = function(receiptData) {
    console.log('Affichage du reçu:', receiptData);
    
    // Créer le contenu du reçu
    const recuHtml = `
        <div class="bg-white rounded-2xl shadow-xl p-8 mx-auto max-w-md" style="font-family: 'Courier New', monospace;">
            <div class="text-center border-b-2 border-gray-800 pb-4 mb-6">
                <h2 class="text-2xl font-bold text-gray-800">REÇU WOYOFAL</h2>
                <p class="text-sm text-gray-600">SENELEC - Crédit Électricité</p>
            </div>
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="font-medium">Client:</span>
                    <span class="text-right">${receiptData.client || 'N/A'}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium">N° Compteur:</span>
                    <span class="text-right font-mono">${receiptData.compteur || 'N/A'}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium">Code Recharge:</span>
                    <span class="text-right font-mono text-lg font-bold text-blue-600">${receiptData.code || 'N/A'}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium">Référence:</span>
                    <span class="text-right font-mono">${receiptData.reference || 'N/A'}</span>
                </div>
                
                <hr class="border-gray-300">
                
                <div class="flex justify-between">
                    <span class="font-medium">Date & Heure:</span>
                    <span class="text-right">${receiptData.date || 'N/A'}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium">Tranche:</span>
                    <span class="text-right">${receiptData.tranche || 'N/A'}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium">Prix unitaire:</span>
                    <span class="text-right">${receiptData.prix || 'N/A'} FCFA/kWh</span>
                </div>
                
                <div class="flex justify-between font-bold text-lg border-t-2 border-gray-800 pt-3">
                    <span>Énergie achetée:</span>
                    <span class="text-green-600">${receiptData.nbreKwt || 'N/A'} kWh</span>
                </div>
                
                <hr class="border-gray-300">
                
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Nouveau solde:</span>
                    <span>${receiptData.nouveau_solde ? Number(receiptData.nouveau_solde).toLocaleString() + ' FCFA' : 'N/A'}</span>
                </div>
            </div>
            
            <div class="text-center mt-6 pt-4 border-t border-gray-300">
                <p class="text-xs text-gray-500">Merci d'utiliser MaxITSA</p>
                <p class="text-xs text-gray-500">Conservez ce reçu</p>
            </div>
            
            <div class="flex gap-2 mt-6">
                <button onclick="printReceipt()" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-print"></i> Imprimer
                </button>
                <button onclick="closeReceipt()" class="flex-1 bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times"></i> Fermer
                </button>
            </div>
        </div>
    `;
    
    // Créer la modal overlay
    const modalOverlay = document.createElement('div');
    modalOverlay.id = 'receiptModal';
    modalOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
    modalOverlay.innerHTML = recuHtml;
    
    // Ajouter à la page
    document.body.appendChild(modalOverlay);
    
    // Fermer en cliquant sur l'overlay
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === modalOverlay) {
            closeReceipt();
        }
    });
};

// Fonction pour imprimer le reçu
window.printReceipt = function() {
    const receiptContent = document.getElementById('receiptModal').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Reçu Woyofal</title>
                <style>
                    body { font-family: 'Courier New', monospace; margin: 20px; }
                    .no-print { display: none; }
                    @media print {
                        .no-print { display: none; }
                        button { display: none; }
                    }
                </style>
            </head>
            <body>
                ${receiptContent}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
};

// Fonction pour fermer le reçu
window.closeReceipt = function() {
    const modal = document.getElementById('receiptModal');
    if (modal) {
        modal.remove();
    }
};

// Fonction d'initialisation principale
function initializePaiementEvents() {
    console.log('=== INITIALISATION EVENEMENTS PAIEMENT ===');
    
    try {
        // Ajouter les event listeners pour les cartes de service
        const serviceCards = document.querySelectorAll('.service-card[data-service]');
        console.log('Cartes de service trouvées:', serviceCards.length);
        
        serviceCards.forEach((card, index) => {
            const service = card.getAttribute('data-service');
            console.log(`Ajout event listener pour carte ${index}: ${service}`);
            
            // Supprimer les anciens événements s'ils existent
            card.removeEventListener('click', handleServiceClick);
            
            // Ajouter le nouvel événement
            card.addEventListener('click', handleServiceClick);
        });
        
        // Initialiser le formulaire Woyofal
        initializeWoyofalForm();
        
        console.log('=== FIN INITIALISATION ===');
    } catch (error) {
        console.error('Erreur lors de l\'initialisation:', error);
    }
}

// Gestionnaire de clic pour les cartes de service
function handleServiceClick(event) {
    console.log('=== CLIC DETECTE SUR CARTE ===');
    const service = this.getAttribute('data-service');
    console.log('Service sélectionné:', service);
    
    // Empêcher la propagation
    event.preventDefault();
    event.stopPropagation();
    
    // Appeler selectService
    selectService(service);
}

// Fonction pour initialiser le formulaire Woyofal
function initializeWoyofalForm() {
    console.log('🚀 Initialisation du formulaire Woyofal');
    
    // Activation forcée des champs AVANT tout autre traitement
    forceInputActivation();
    
    try {
        const woyofalForm = document.getElementById('woyofalForm');
        if (!woyofalForm) {
            console.warn('⚠️ Formulaire woyofalForm non trouvé');
            return;
        }
        
        console.log('✅ Formulaire woyofalForm trouvé');
        
        // Vérifier si l'événement n'est pas déjà attaché
        if (woyofalForm.hasAttribute('data-initialized')) {
            console.log('⚠️ Formulaire déjà initialisé, nouvelle activation des champs');
            forceInputActivation(); // Re-activer au cas où
            return;
        }
        
        // Marquer comme initialisé
        woyofalForm.setAttribute('data-initialized', 'true');
        
        // Ajouter des logs pour débugger les champs de saisie et diagnostic automatique
        const numeroCompteurField = document.getElementById('numeroCompteur');
        const montantField = document.getElementById('montant');
        
        console.log('=== DIAGNOSTIC CHAMPS ===');
        console.log('Champ numeroCompteur:', numeroCompteurField);
        console.log('Champ montant:', montantField);
        
        if (numeroCompteurField) {
            console.log('numeroCompteur - disabled:', numeroCompteurField.disabled);
            console.log('numeroCompteur - readOnly:', numeroCompteurField.readOnly);
            console.log('numeroCompteur - style.pointerEvents:', window.getComputedStyle(numeroCompteurField).pointerEvents);
            console.log('numeroCompteur - tabIndex:', numeroCompteurField.tabIndex);
            
            numeroCompteurField.addEventListener('focus', () => {
                console.log('Focus sur champ numéro compteur');
                hideFieldError('numero');
                hideFieldSuccess('numero');
            });
            numeroCompteurField.addEventListener('input', (e) => {
                console.log('Input numeroCompteur:', e.target.value);
                hideFieldError('numero');
                hideFieldSuccess('numero');
                
                // Vérification en temps réel avec délai (debounce)
                clearTimeout(window.verificationTimeout);
                window.verificationTimeout = setTimeout(() => {
                    verifierNumeroCompteur(e.target.value.trim());
                }, 500); // Réduit à 500ms pour plus de réactivité
            });
            numeroCompteurField.addEventListener('blur', (e) => {
                // Vérification immédiate quand l'utilisateur quitte le champ
                if (e.target.value.trim()) {
                    clearTimeout(window.verificationTimeout);
                    verifierNumeroCompteur(e.target.value.trim());
                }
            });
            numeroCompteurField.addEventListener('click', () => console.log('Clic sur champ numéro compteur'));
            numeroCompteurField.addEventListener('keydown', (e) => console.log('Keydown numéro compteur:', e.key));
            
            // Test de focus forcé
            setTimeout(() => {
                console.log('Test de focus automatique sur numeroCompteur...');
                numeroCompteurField.focus();
            }, 2000);
        }
        
        if (montantField) {
            console.log('montant - disabled:', montantField.disabled);
            console.log('montant - readOnly:', montantField.readOnly);
            console.log('montant - style.pointerEvents:', window.getComputedStyle(montantField).pointerEvents);
            console.log('montant - tabIndex:', montantField.tabIndex);
            
            montantField.addEventListener('focus', () => {
                console.log('Focus sur champ montant');
                hideFieldError('montant');
            });
            montantField.addEventListener('input', (e) => {
                console.log('Input montant:', e.target.value);
                hideFieldError('montant');
            });
            montantField.addEventListener('click', () => console.log('Clic sur champ montant'));
            montantField.addEventListener('keydown', (e) => console.log('Keydown montant:', e.key));
        }
        
        console.log('=== FIN DIAGNOSTIC ===');
        
        woyofalForm.addEventListener('submit', function(e) {
            console.log('Formulaire Woyofal soumis');
            e.preventDefault();
            
            const numeroCompteur = document.getElementById('numeroCompteur').value;
            const montant = document.getElementById('montant').value;
            const btnAcheter = document.getElementById('btnAcheter');
            const loadingSpinner = document.getElementById('loadingSpinner');
            
            console.log('Valeurs du formulaire:', { numeroCompteur, montant });
            
            // Réinitialiser les erreurs
            hideFieldError('numero');
            hideFieldError('montant');
            
            // Validation côté client
            let hasErrors = false;
            if (!numeroCompteur.trim()) {
                showFieldError('numero', 'Le numéro de compteur est obligatoire');
                hasErrors = true;
            }
            
            if (!montant || montant < 500) {
                showFieldError('montant', 'Le montant minimum est de 500 FCFA');
                hasErrors = true;
            }
            
            if (hasErrors) return;
            
            // Affichage du loader
            if (btnAcheter) btnAcheter.disabled = true;
            if (loadingSpinner) loadingSpinner.classList.remove('hidden');
            
            // Envoi de la requête
            fetch('/woyofal/acheter', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    numero_compteur: numeroCompteur,
                    montant: parseFloat(montant)
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Réponse serveur:', data);
                if (data.statut === 'success') {
                    showAlert('Achat effectué avec succès !', 'success');
                    if (data.data) {
                        // Afficher le reçu avec toutes les informations
                        showReceipt(data.data);
                    }
                    woyofalForm.reset();
                    // Masquer les messages de succès des champs après reset
                    hideFieldSuccess('numero');
                    hideFieldSuccess('montant');
                } else {
                    // Afficher les erreurs spécifiques aux champs si disponibles
                    if (data.errors) {
                        for (const [field, message] of Object.entries(data.errors)) {
                            showFieldError(field, message);
                        }
                    } else {
                        showAlert(data.message || 'Erreur lors de l\'achat', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showAlert('Erreur de connexion', 'error');
            })
            .finally(() => {
                if (btnAcheter) btnAcheter.disabled = false;
                if (loadingSpinner) loadingSpinner.classList.add('hidden');
            });
        });
        
        console.log('Événement submit ajouté au formulaire Woyofal');
    } catch (error) {
        console.error('Erreur lors de l\'initialisation du formulaire:', error);
    }
}

// Multiples stratégies d'initialisation pour garantir le bon fonctionnement
console.log('DOM readyState:', document.readyState);

// Stratégie 1: Si le DOM est déjà chargé
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePaiementEvents);
} else {
    console.log('DOM déjà chargé, initialisation immédiate');
    initializePaiementEvents();
}

// Stratégie 2: Délai pour le contenu AJAX
setTimeout(() => {
    console.log('Initialisation différée (100ms)');
    initializePaiementEvents();
}, 100);

// Stratégie 3: Délai plus long au cas où + activation forcée répétée
setTimeout(() => {
    console.log('🔄 Initialisation différée (500ms) + activation forcée');
    forceInputActivation();
    initializePaiementEvents();
}, 500);

// Stratégie 4: Activation encore plus tardive pour les cas difficiles
setTimeout(() => {
    console.log('🔧 Activation de sécurité (2 secondes)');
    forceInputActivation();
}, 2000);

console.log('=== FIN SCRIPT PAIEMENT ===');
</script>
