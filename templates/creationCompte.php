<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAXIT SA - Créer un compte</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-8">
    <div class="bg-white rounded-3xl shadow-xl p-8 w-full max-w-md mx-4">
        <!-- Titre -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-blue-600 mb-4">MAXITSA</h1>
            <h2 class="text-lg font-semibold text-black">Créer votre compte principal</h2>
        </div>

        <!-- Formulaire -->
        <form method="POST" action="/creer-compte/store" enctype="multipart/form-data" class="space-y-6">
            <?php if (isset($errors['general'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <?= displayError($errors['general']) ?>
                </div>
            <?php endif; ?>

            <!-- Section CNI - Premier champ -->
            <div>
                <h3 class="text-base font-medium text-gray-700 mb-4">Vérification d'identité</h3>
                
                <!-- Numéro CNI -->
                <div class="mb-4">
                    <div class="relative">
                        <input 
                            type="text" 
                            id="numeroCNI"
                            name="numeroCNI"
                            value="<?= htmlspecialchars($data['numeroCNI'] ?? '') ?>"
                            placeholder="Numéro CNI (13 caractères)"
                            maxlength="13"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <div id="loadingCNI" class="absolute right-3 top-3 hidden">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                        </div>
                    </div>
                    <?php if (isset($errors['numeroCNI'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= displayError($errors['numeroCNI']) ?></p>
                    <?php endif; ?>
                    
                    <!-- Zone d'affichage des informations du citoyen -->
                    <div id="citoyenInfo" class="hidden mt-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-green-700 font-medium">Citoyen trouvé!</span>
                        </div>
                        <div id="citoyenDetails" class="text-sm text-green-600 space-y-1">
                            <!-- Les détails seront remplis par JavaScript -->
                        </div>
                    </div>
                    
                    <!-- Zone d'affichage des erreurs -->
                    <div id="citoyenError" class="hidden mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span id="citoyenErrorMessage" class="text-red-700 text-sm"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section informations personnelles -->
            <div>
                <h3 class="text-base font-medium text-gray-700 mb-4">Informations personnelles</h3>
                
                <!-- Nom et Prénom -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <input 
                            type="text" 
                            name="nom"
                            value="<?= htmlspecialchars($data['nom'] ?? '') ?>"
                            placeholder="Nom"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            disabled
                        >
                        <?php if (isset($errors['nom'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= displayError($errors['nom']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="prenom"
                            value="<?= htmlspecialchars($data['prenom'] ?? '') ?>"
                            placeholder="Prénom"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            disabled
                        >
                        <?php if (isset($errors['prenom'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= displayError($errors['prenom']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Login -->
                <div class="mb-4">
                    <input 
                        type="text" 
                        name="login"
                        value="<?= htmlspecialchars($data['login'] ?? '') ?>"
                        placeholder="Login"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        disabled
                    >
                    <?php if (isset($errors['login'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= displayError($errors['login']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Numéro de téléphone -->
                <div class="mb-4">
                    <input 
                        type="tel" 
                        name="numeroTelephone"
                        value="<?= htmlspecialchars($data['numeroTelephone'] ?? '') ?>"
                        placeholder="Numéro de téléphone"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        disabled
                    >
                    <?php if (isset($errors['numeroTelephone'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= displayError($errors['numeroTelephone']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Adresse -->
                <div class="mb-4">
                    <textarea 
                        name="adresse"
                        placeholder="Adresse"
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        disabled
                    ><?= htmlspecialchars($data['adresse'] ?? '') ?></textarea>
                    <?php if (isset($errors['adresse'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= displayError($errors['adresse']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Section document d'identité -->
            <div>
                <h3 class="text-base font-medium text-gray-700 mb-4">Document d'identité</h3>

                <!-- Photos CNI -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Photo recto CNI</label>
                        <input 
                            type="file" 
                            name="photorecto"
                            accept="image/*"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            disabled
                        >
                        <?php if (isset($errors['photorecto'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= displayError($errors['photorecto']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Photo verso CNI</label>
                        <input 
                            type="file" 
                            name="photoverso"
                            accept="image/*"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            disabled
                        >
                        <?php if (isset($errors['photoverso'])): ?>
                            <p class="text-red-500 text-sm mt-1"><?= displayError($errors['photoverso']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Mots de passe -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <input 
                        type="password" 
                        name="mot_de_passe"
                        placeholder="Mot de passe"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        disabled
                    >
                    <?php if (isset($errors['mot_de_passe'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= displayError($errors['mot_de_passe']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <input 
                        type="password" 
                        name="confirmer_mot_de_passe"
                        placeholder="Confirmer le mot de passe"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        disabled
                    >
                    <?php if (isset($errors['confirmer_mot_de_passe'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= displayError($errors['confirmer_mot_de_passe']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Messages d'erreur d'upload -->
            <?php if (isset($errors['upload'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <?= displayError($errors['upload']) ?>
                </div>
            <?php endif; ?>

            <!-- Bouton Suivant -->
            <button 
                type="submit"
                id="submitBtn"
                class="w-full bg-gray-400 text-white font-semibold py-4 px-6 rounded-xl transition duration-200 ease-in-out cursor-not-allowed"
                disabled
            >
                Créer mon compte
            </button>
        </form>

        <!-- Lien vers la connexion -->
        <div class="text-center mt-6">
            <a href="/connexion" class="text-blue-600 hover:text-blue-800 text-sm transition duration-200">
                Déjà un compte ? Se connecter
            </a>
        </div>
    </div>

    <!-- Script JavaScript externe -->
    <script src="/js/creation-compte.js"></script>
</body>
</html>