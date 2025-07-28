/**
 * Gestion du formulaire de création de compte
 * Fonctionnalité de récupération automatique des données du citoyen via CNI
 */

console.log('🚀 Script creation-compte.js chargé');

document.addEventListener('DOMContentLoaded', function() {
    console.log('📱 DOMContentLoaded déclenché');
    
    const cniInput = document.getElementById('numeroCNI');
    const loadingCNI = document.getElementById('loadingCNI');
    const citoyenInfo = document.getElementById('citoyenInfo');
    const citoyenDetails = document.getElementById('citoyenDetails');
    const citoyenError = document.getElementById('citoyenError');
    const citoyenErrorMessage = document.getElementById('citoyenErrorMessage');
    
    console.log('🔍 Éléments trouvés:', {
        cniInput: !!cniInput,
        loadingCNI: !!loadingCNI,
        citoyenInfo: !!citoyenInfo,
        citoyenDetails: !!citoyenDetails,
        citoyenError: !!citoyenError,
        citoyenErrorMessage: !!citoyenErrorMessage
    });
    
    // Champs du formulaire à remplir automatiquement
    const nomInput = document.querySelector('input[name="nom"]');
    const prenomInput = document.querySelector('input[name="prenom"]');
    const adresseInput = document.querySelector('textarea[name="adresse"]');
    const loginInput = document.querySelector('input[name="login"]');
    const telephoneInput = document.querySelector('input[name="numeroTelephone"]');
    const motDePasseInput = document.querySelector('input[name="mot_de_passe"]');
    const confirmerMotDePasseInput = document.querySelector('input[name="confirmer_mot_de_passe"]');
    const photoRectoInput = document.querySelector('input[name="photorecto"]');
    const photoVersoInput = document.querySelector('input[name="photoverso"]');
    const submitButton = document.querySelector('#submitBtn') || document.querySelector('button[type="submit"]');
    
    // Tous les champs à bloquer pendant le fetch
    const fieldsToBlock = [
        nomInput, prenomInput, adresseInput, loginInput, telephoneInput,
        motDePasseInput, confirmerMotDePasseInput, photoRectoInput, photoVersoInput,
        submitButton
    ].filter(field => field !== null);
    
    let timeoutId = null;
    let isLoading = false;

    // Écouteur d'événement sur le champ CNI
    if (cniInput) {
        console.log('✅ Ajout de l\'écouteur sur le champ CNI');
        cniInput.addEventListener('input', function() {
            const cni = this.value.trim();
            console.log(`⌨️ Input CNI: "${cni}" (${cni.length} caractères)`);
            
            // Cacher les messages précédents
            hideMessages();
            
            // Si CNI a exactement 13 caractères, faire le fetch
            if (cni.length === 13 && !isLoading) {
                console.log('🎯 13 caractères détectés! Démarrage du fetch...');
                // Débounce pour éviter trop de requêtes
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    console.log('⏰ Timeout déclenché, lancement du fetch');
                    fetchCitoyen(cni);
                }, 300); // Réduit le délai pour une meilleure réactivité
            } else if (cni.length < 13) {
                console.log(`⏳ Attente... ${13 - cni.length} caractères restants`);
                console.log('🔒 Re-blocage des champs - CNI incomplet');
                
                // Bloquer les champs si le CNI devient incomplet
                disableAllFieldsExceptCNI();
                
                // Vider les champs pré-remplis
                clearFormFields();
            }
        });
    } else {
        console.error('❌ Élément numeroCNI non trouvé!');
    }

    /**
     * Récupère les données du citoyen via l'API
     * @param {string} cni - Numéro CNI à rechercher
     */
    async function fetchCitoyen(cni) {
        console.log(`🌐 Démarrage du fetch pour CNI: ${cni}`);
        try {
            // Marquer comme en cours de chargement
            isLoading = true;
            console.log('🔒 Blocage des champs...');
            
            // Afficher le loading et bloquer les champs
            showLoading();
            disableAllFields();
            
            // Faire le fetch vers notre endpoint local
            console.log('📡 Envoi de la requête...');
            const response = await fetch(`/creer-compte/citoyen?cni=${cni}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            
            console.log(`📨 Réponse reçue: ${response.status} ${response.statusText}`);
            const data = await response.json();
            console.log('📄 Données JSON:', data);
            
            // Cacher le loading et retirer l'overlay
            hideLoading();
            removeLoadingOverlay();
            isLoading = false;
            
            if (response.ok && data.statut === 'success' && data.data) {
                console.log('✅ Citoyen trouvé:', data.data);
                console.log('🔓 Déblocage des champs - Citoyen trouvé');
                
                // Débloquer les champs SEULEMENT si le citoyen est trouvé
                enableAllFields();
                
                // Afficher les informations du citoyen
                displayCitoyenInfo(data.data);
                
                // Remplir automatiquement les champs du formulaire
                fillFormFields(data.data);
                
            } else {
                console.log('❌ Erreur ou citoyen non trouvé:', data.message);
                console.log('🔒 Champs restent bloqués - Citoyen non trouvé');
                
                // NE PAS débloquer les champs en cas d'erreur
                // Les champs restent disabled
                
                // Afficher l'erreur
                const errorMsg = data.message || 'Citoyen non trouvé';
                displayError(errorMsg);
            }
            
        } catch (error) {
            console.error('💥 Erreur lors de la récupération du citoyen:', error);
            console.log('🔒 Champs restent bloqués - Erreur de connexion');
            
            hideLoading();
            removeLoadingOverlay();
            isLoading = false;
            
            // NE PAS débloquer les champs en cas d'erreur de connexion
            // Les champs restent disabled
            
            displayError('Erreur de connexion à l\'API. Vérifiez que le service est accessible.');
        }
    }

    /**
     * Affiche les informations du citoyen trouvé
     * @param {object} citoyen - Données du citoyen
     */
    function displayCitoyenInfo(citoyen) {
        hideError();
        
        let details = '';
        if (citoyen.nom && citoyen.prenom) {
            details += `<div><strong>Nom complet:</strong> ${citoyen.prenom} ${citoyen.nom}</div>`;
        }
        if (citoyen.date_naissance) {
            details += `<div><strong>Date de naissance:</strong> ${citoyen.date_naissance}</div>`;
        }
        if (citoyen.lieu_naissance) {
            details += `<div><strong>Lieu de naissance:</strong> ${citoyen.lieu_naissance}</div>`;
        }
        if (citoyen.sexe) {
            details += `<div><strong>Sexe:</strong> ${citoyen.sexe === 'M' ? 'Masculin' : 'Féminin'}</div>`;
        }
        if (citoyen.adresse) {
            details += `<div><strong>Adresse:</strong> ${citoyen.adresse}</div>`;
        }
        
        if (citoyenDetails) {
            citoyenDetails.innerHTML = details;
        }
        
        if (citoyenInfo) {
            citoyenInfo.classList.remove('hidden');
        }
    }

    /**
     * Affiche un message d'erreur
     * @param {string} message - Message d'erreur à afficher
     */
    function displayError(message) {
        hideSuccess();
        
        if (citoyenErrorMessage) {
            citoyenErrorMessage.textContent = message;
        }
        
        if (citoyenError) {
            citoyenError.classList.remove('hidden');
        }
    }

    /**
     * Remplit automatiquement les champs du formulaire
     * @param {object} citoyen - Données du citoyen
     */
    function fillFormFields(citoyen) {
        if (citoyen.nom && nomInput) {
            nomInput.value = citoyen.nom;
        }
        if (citoyen.prenom && prenomInput) {
            prenomInput.value = citoyen.prenom;
        }
        if (citoyen.adresse && adresseInput) {
            adresseInput.value = citoyen.adresse;
        }
    }

    /**
     * Affiche le spinner de chargement
     */
    function showLoading() {
        if (loadingCNI) {
            loadingCNI.classList.remove('hidden');
        }
        
        // Ajouter un indicateur visuel sur le champ CNI
        if (cniInput) {
            cniInput.style.backgroundColor = '#f3f4f6';
            cniInput.style.cursor = 'wait';
        }
    }

    /**
     * Cache le spinner de chargement
     */
    function hideLoading() {
        if (loadingCNI) {
            loadingCNI.classList.add('hidden');
        }
        
        // Retirer l'indicateur visuel du champ CNI
        if (cniInput) {
            cniInput.style.backgroundColor = '';
            cniInput.style.cursor = '';
        }
    }

    /**
     * Désactive tous les champs du formulaire
     */
    function disableAllFields() {
        fieldsToBlock.forEach(field => {
            if (field) {
                field.disabled = true;
                field.style.opacity = '0.5';
                field.style.cursor = 'not-allowed';
            }
        });
        
        // Ajouter un overlay pour indiquer le chargement
        addLoadingOverlay();
    }

    /**
     * Réactive tous les champs du formulaire
     */
    function enableAllFields() {
        fieldsToBlock.forEach(field => {
            if (field) {
                field.disabled = false;
                field.style.opacity = '';
                field.style.cursor = '';
            }
        });
        
        // Activer spécifiquement le bouton et changer sa couleur
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.className = 'w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-xl transition duration-200 ease-in-out transform hover:scale-105';
        }
        
        // Note: L'overlay est supprimé après le fetch, pas ici
    }

    /**
     * Ajoute un overlay de chargement sur le formulaire
     */
    function addLoadingOverlay() {
        const form = document.querySelector('form');
        if (form && !form.querySelector('.loading-overlay')) {
            const overlay = document.createElement('div');
            overlay.className = 'loading-overlay';
            overlay.style.cssText = `
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(255, 255, 255, 0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                border-radius: 1rem;
            `;
            overlay.innerHTML = `
                <div style="text-align: center; color: #374151;">
                    <div style="margin-bottom: 8px;">
                        <div style="
                            width: 40px;
                            height: 40px;
                            border: 4px solid #e5e7eb;
                            border-top: 4px solid #3b82f6;
                            border-radius: 50%;
                            animation: spin 1s linear infinite;
                            margin: 0 auto;
                        "></div>
                    </div>
                    <div style="font-weight: 500;">Recherche en cours...</div>
                    <div style="font-size: 0.875rem; margin-top: 4px;">Veuillez patienter</div>
                </div>
            `;
            
            // Ajouter l'animation CSS
            if (!document.querySelector('#loading-animation-style')) {
                const style = document.createElement('style');
                style.id = 'loading-animation-style';
                style.textContent = `
                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                `;
                document.head.appendChild(style);
            }
            
            form.style.position = 'relative';
            form.appendChild(overlay);
        }
    }

    /**
     * Retire l'overlay de chargement
     */
    function removeLoadingOverlay() {
        const overlay = document.querySelector('.loading-overlay');
        if (overlay) {
            overlay.remove();
        }
    }

    /**
     * Cache tous les messages
     */
    function hideMessages() {
        hideSuccess();
        hideError();
    }

    /**
     * Cache le message de succès
     */
    function hideSuccess() {
        if (citoyenInfo) {
            citoyenInfo.classList.add('hidden');
        }
    }

    /**
     * Cache le message d'erreur
     */
    function hideError() {
        if (citoyenError) {
            citoyenError.classList.add('hidden');
        }
    }

    /**
     * Désactive tous les champs sauf le CNI (état initial)
     */
    function disableAllFieldsExceptCNI() {
        fieldsToBlock.forEach(field => {
            if (field) {
                field.disabled = true;
                field.style.opacity = '0.5';
                field.style.cursor = 'not-allowed';
            }
        });
        
        // S'assurer que le bouton est dans l'état initial (gris)
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.className = 'w-full bg-gray-400 text-white font-semibold py-4 px-6 rounded-xl transition duration-200 ease-in-out cursor-not-allowed';
        }
        
        // Retirer l'overlay si présent (pas de chargement en cours)
        removeLoadingOverlay();
    }

    /**
     * Vide les champs pré-remplis du formulaire
     */
    function clearFormFields() {
        if (nomInput) nomInput.value = '';
        if (prenomInput) prenomInput.value = '';
        if (adresseInput) adresseInput.value = '';
        if (loginInput) loginInput.value = '';
        if (telephoneInput) telephoneInput.value = '';
    }
});
