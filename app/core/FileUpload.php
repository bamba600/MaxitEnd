<?php

namespace App\Core;

class FileUpload
{
    private string $uploadDir;
    private array $allowedExtensions;
    private array $allowedMimeTypes;
    private int $maxFileSize;

    public function __construct(
        string $uploadDir = null,
        array $allowedExtensions = null,
        int $maxFileSize = null
    ) {
        // Utiliser la configuration .env si disponible
        $this->uploadDir = $uploadDir ?? (defined('UPLOAD_PATH') ? UPLOAD_PATH : 'public/uploads/');
        $this->uploadDir = rtrim($this->uploadDir, '/') . '/';
        
        $this->allowedExtensions = $allowedExtensions ?? ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
        $this->maxFileSize = $maxFileSize ?? (defined('UPLOAD_MAX_SIZE') ? UPLOAD_MAX_SIZE : 5242880); // 5MB
        
        // Types MIME autorisés basés sur votre base de données
        $this->allowedMimeTypes = [
            'image/jpeg',
            'image/jpg', 
            'image/png',
            'image/gif',
            'application/pdf'
        ];
        
        // Créer le dossier d'upload s'il n'existe pas
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    /**
     * Upload un fichier
     */
    public function upload(array $file, string $prefix = ''): array
    {
        // Vérifier si le fichier a été uploadé sans erreur
        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'error' => $this->getUploadErrorMessage($file['error'] ?? UPLOAD_ERR_NO_FILE),
                'filename' => null
            ];
        }

        // Vérifier la taille du fichier
        if ($file['size'] > $this->maxFileSize) {
            return [
                'success' => false,
                'error' => 'Le fichier est trop volumineux. Taille maximale autorisée: ' . $this->formatBytes($this->maxFileSize),
                'filename' => null
            ];
        }

        // Vérifier l'extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions)) {
            return [
                'success' => false,
                'error' => 'Extension non autorisée. Extensions acceptées: ' . implode(', ', $this->allowedExtensions),
                'filename' => null
            ];
        }

        // Vérifier le type MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            return [
                'success' => false,
                'error' => 'Type de fichier non autorisé. Types acceptés: ' . implode(', ', $this->allowedMimeTypes),
                'filename' => null
            ];
        }

        // Vérifier que c'est vraiment une image ou un PDF
        if (strpos($mimeType, 'image/') === 0) {
            if (!$this->isValidImage($file['tmp_name'])) {
                return [
                    'success' => false,
                    'error' => 'Le fichier n\'est pas une image valide',
                    'filename' => null
                ];
            }
        }

        // Générer un nom de fichier unique
        $filename = $this->generateUniqueFilename($prefix, $extension);
        $filepath = $this->uploadDir . $filename;

        // Déplacer le fichier
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return [
                'success' => true,
                'error' => null,
                'filename' => $filename,
                'filepath' => $filepath,
                'size' => $file['size'],
                'original_name' => $file['name']
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Erreur lors du déplacement du fichier',
                'filename' => null
            ];
        }
    }

    /**
     * Upload multiple fichiers
     */
    public function uploadMultiple(array $files, string $prefix = ''): array
    {
        $results = [];
        
        foreach ($files as $key => $file) {
            $filePrefix = $prefix ? $prefix . '_' . $key : $key;
            $results[$key] = $this->upload($file, $filePrefix);
        }
        
        return $results;
    }

    /**
     * Supprimer un fichier uploadé
     */
    public function delete(string $filename): bool
    {
        $filepath = $this->uploadDir . $filename;
        
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        
        return false;
    }

    /**
     * Vérifier si un fichier est une image valide
     */
    private function isValidImage(string $tmpName): bool
    {
        $imageInfo = getimagesize($tmpName);
        return $imageInfo !== false;
    }

    /**
     * Générer un nom de fichier unique (compatible avec votre format existant)
     */
    private function generateUniqueFilename(string $prefix, string $extension): string
    {
        $timestamp = time();
        $uniqueId = uniqid();
        
        if ($prefix) {
            // Format compatible avec vos données: recto_1752091750_686ecc66ec81d.jpg
            return $prefix . '_' . $timestamp . '_' . $uniqueId . '.' . $extension;
        }
        
        return $timestamp . '_' . $uniqueId . '.' . $extension;
    }

    /**
     * Upload spécialement pour les documents CNI (recto/verso)
     */
    public function uploadDocument(array $file, string $type, int $userId): array
    {
        $prefix = $type; // 'recto' ou 'verso'
        return $this->upload($file, $prefix);
    }

    /**
     * Upload multiple pour recto et verso
     */
    public function uploadRectoVerso(array $rectoFile, array $versoFile): array
    {
        $results = [
            'recto' => $this->uploadDocument($rectoFile, 'recto', 0),
            'verso' => $this->uploadDocument($versoFile, 'verso', 0)
        ];
        
        // Vérifier que les deux uploads ont réussi
        $success = $results['recto']['success'] && $results['verso']['success'];
        
        return [
            'success' => $success,
            'recto' => $results['recto'],
            'verso' => $results['verso'],
            'error' => $success ? null : 'Erreur lors de l\'upload des documents'
        ];
    }

    /**
     * Obtenir le message d'erreur d'upload
     */
    private function getUploadErrorMessage(int $errorCode): string
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return 'Le fichier dépasse la taille maximale autorisée par PHP';
            case UPLOAD_ERR_FORM_SIZE:
                return 'Le fichier dépasse la taille maximale autorisée par le formulaire';
            case UPLOAD_ERR_PARTIAL:
                return 'Le fichier n\'a été que partiellement uploadé';
            case UPLOAD_ERR_NO_FILE:
                return 'Aucun fichier n\'a été uploadé';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Dossier temporaire manquant';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Échec de l\'écriture du fichier sur le disque';
            case UPLOAD_ERR_EXTENSION:
                return 'Une extension PHP a arrêté l\'upload';
            default:
                return 'Erreur d\'upload inconnue';
        }
    }

    /**
     * Formater la taille en bytes de manière lisible
     */
    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Obtenir la liste des extensions autorisées
     */
    public function getAllowedExtensions(): array
    {
        return $this->allowedExtensions;
    }

    /**
     * Modifier les extensions autorisées
     */
    public function setAllowedExtensions(array $extensions): self
    {
        $this->allowedExtensions = $extensions;
        return $this;
    }

    /**
     * Obtenir la taille maximale autorisée
     */
    public function getMaxFileSize(): int
    {
        return $this->maxFileSize;
    }

    /**
     * Modifier la taille maximale autorisée
     */
    public function setMaxFileSize(int $size): self
    {
        $this->maxFileSize = $size;
        return $this;
    }

    /**
     * Obtenir le répertoire d'upload
     */
    public function getUploadDir(): string
    {
        return $this->uploadDir;
    }

    /**
     * Valider un fichier sans l'uploader (pour preview)
     */
    public function validateFile(array $file): array
    {
        // Vérifier si le fichier a été uploadé sans erreur
        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return [
                'valid' => false,
                'error' => $this->getUploadErrorMessage($file['error'] ?? UPLOAD_ERR_NO_FILE)
            ];
        }

        // Vérifier la taille du fichier
        if ($file['size'] > $this->maxFileSize) {
            return [
                'valid' => false,
                'error' => 'Le fichier est trop volumineux. Taille maximale autorisée: ' . $this->formatBytes($this->maxFileSize)
            ];
        }

        // Vérifier l'extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions)) {
            return [
                'valid' => false,
                'error' => 'Extension non autorisée. Extensions acceptées: ' . implode(', ', $this->allowedExtensions)
            ];
        }

        return [
            'valid' => true,
            'extension' => $extension,
            'size' => $file['size'],
            'name' => $file['name']
        ];
    }

    /**
     * Obtenir des informations sur un fichier uploadé
     */
    public function getFileInfo(string $filename): ?array
    {
        $filepath = $this->uploadDir . $filename;
        
        if (!file_exists($filepath)) {
            return null;
        }
        
        return [
            'filename' => $filename,
            'filepath' => $filepath,
            'size' => filesize($filepath),
            'extension' => strtolower(pathinfo($filename, PATHINFO_EXTENSION)),
            'created' => filectime($filepath),
            'modified' => filemtime($filepath)
        ];
    }

    /**
     * Nettoyer les anciens fichiers
     */
    public function cleanOldFiles(int $daysOld = 30): int
    {
        $deleted = 0;
        $cutoffTime = time() - ($daysOld * 24 * 60 * 60);
        
        $files = glob($this->uploadDir . '*');
        foreach ($files as $file) {
            if (is_file($file) && filemtime($file) < $cutoffTime) {
                if (unlink($file)) {
                    $deleted++;
                }
            }
        }
        
        return $deleted;
    }
}
