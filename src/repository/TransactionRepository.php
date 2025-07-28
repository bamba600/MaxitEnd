<?php

namespace App\Repository;

use App\Core\Abstract\AbstractRepository;
use App\Entity\Transaction;
use App\Entity\TypeTransaction;
use PDO;

class TransactionRepository extends AbstractRepository
{
    protected string $table = 'transaction';

    public function findByCompteId(int $compteId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE compte_id = :compte_id ORDER BY date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['compte_id' => $compteId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $transactions = [];
        foreach ($results as $data) {
            $transactions[] = $this->toObject($data);
        }
        
        return $transactions;
    }

    public function findDernieresTransactionsByCompteId(int $compteId, int $limit = 10): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE compte_id = :compte_id ORDER BY date DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':compte_id', $compteId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $transactions = [];
        foreach ($results as $data) {
            $transactions[] = $this->toObject($data);
        }
        
        return $transactions;
    }

    public function findByReference(string $reference): ?Transaction
    {
        $sql = "SELECT * FROM {$this->table} WHERE reference = :reference";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reference' => $reference]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return $this->toObject($data);
    }

    public function create(Transaction $transaction): int
    {
        // Vérifier si la colonne reference existe
        $hasReference = $this->hasColumn('reference');
        
        $data = [
            'date' => $transaction->getDate()->format('Y-m-d H:i:s'),
            'montant' => $transaction->getMontant(),
            'compte_id' => $transaction->getCompteId(),
            'type' => $transaction->getType(),
            'description' => $transaction->getDescription(),
            'statut' => 'validee' // Définir le statut par défaut
        ];

        // Ajouter la référence seulement si la colonne existe
        if ($hasReference && $transaction->getReference()) {
            $data['reference'] = $transaction->getReference();
        }

        return $this->save($data);
    }

    public function getTransactionsByPeriode(int $compteId, string $dateDebut, string $dateFin): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE compte_id = :compte_id AND date BETWEEN :date_debut AND :date_fin ORDER BY date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'compte_id' => $compteId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin
        ]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $transactions = [];
        foreach ($results as $data) {
            $transactions[] = $this->toObject($data);
        }
        
        return $transactions;
    }

    public function getStatistiquesTransactions(int $compteId): array
    {
        $sql = "SELECT 
                    type,
                    COUNT(*) as nombre,
                    SUM(montant) as total
                FROM {$this->table} 
                WHERE compte_id = :compte_id 
                GROUP BY type";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['compte_id' => $compteId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Alias pour findByCompteId avec limite
     */
    public function findByCompte(int $compteId, int $limit = null): array
    {
        if ($limit) {
            return $this->findDernieresTransactionsByCompteId($compteId, $limit);
        }
        return $this->findByCompteId($compteId);
    }

    /**
     * Trouve toutes les transactions d'un utilisateur (tous ses comptes)
     */
    public function findByUtilisateur(int $utilisateurId, int $limit = null): array
    {
        $limitClause = $limit ? "LIMIT :limit" : "";
        $sql = "SELECT t.* FROM {$this->table} t 
                INNER JOIN compte c ON t.compte_id = c.id 
                WHERE c.utilisateur_id = :utilisateur_id 
                ORDER BY t.date DESC {$limitClause}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':utilisateur_id', $utilisateurId, PDO::PARAM_INT);
        
        if ($limit) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $transactions = [];
        foreach ($results as $data) {
            $transactions[] = $this->toObject($data);
        }
        
        return $transactions;
    }

    /**
     * Trouve les transactions récentes d'un utilisateur
     */
    public function findRecentByUtilisateur(int $utilisateurId, int $limit = 10): array
    {
        return $this->findByUtilisateur($utilisateurId, $limit);
    }

    protected function toObject(array $data): object
    {
        $transaction = new Transaction();
        $transaction->setId($data['id']);
        $transaction->setDate(new \DateTime($data['date']));
        $transaction->setMontant($data['montant']);
        $transaction->setCompteId($data['compte_id']);
        $transaction->setType($data['type']);
        $transaction->setDescription($data['description'] ?? '');
        
        // Gérer la référence seulement si elle existe
        if (isset($data['reference'])) {
            $transaction->setReference($data['reference']);
        } else {
            $transaction->setReference('');
        }

        return $transaction;
    }
}
