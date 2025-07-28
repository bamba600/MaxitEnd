<?php

namespace App\Service;

use App\Repository\TransactionRepository;
use App\Repository\CompteRepository;
use App\Entity\Transaction;
use App\Core\App;

class TransactionService
{
    private TransactionRepository $transactionRepository;
    private CompteRepository $compteRepository;

    public function __construct()
    {
        $this->transactionRepository = new TransactionRepository();
        $this->compteRepository = new CompteRepository();
    }

    /**
     * Effectuer un dépôt
     */
    public function deposit(int $compteId, float $montant, string $description = ''): bool
    {
        if ($montant <= 0) {
            return false;
        }

        $compte = $this->compteRepository->findCompteById($compteId);
        if (!$compte) {
            return false;
        }

        // Créer la transaction
        $transaction = new Transaction();
        $transaction->setCompteId($compteId);
        $transaction->setType('depot');
        $transaction->setMontant($montant);
        $transaction->setDescription($description ?: 'Dépôt');
        $transaction->setDate(new \DateTime());
        $transaction->setReference('DEP_' . time() . '_' . uniqid());

        // Mettre à jour le solde du compte
        $nouveauSolde = $compte->getSolde() + $montant;
        
        // Sauvegarder la transaction et mettre à jour le solde
        $this->transactionRepository->create($transaction);
        $this->compteRepository->updateSolde($compteId, $nouveauSolde);

        return true;
    }

    /**
     * Effectuer un retrait
     */
    public function withdraw(int $compteId, float $montant, string $description = ''): bool
    {
        if ($montant <= 0) {
            return false;
        }

        $compte = $this->compteRepository->findCompteById($compteId);
        if (!$compte) {
            return false;
        }

        // Vérifier si le solde est suffisant
        if ($compte->getSolde() < $montant) {
            return false;
        }

        // Créer la transaction
        $transaction = new Transaction();
        $transaction->setCompteId($compteId);
        $transaction->setType('retrait');
        $transaction->setMontant($montant);
        $transaction->setDescription($description ?: 'Retrait');
        $transaction->setDate(new \DateTime());
        $transaction->setReference('RET_' . time() . '_' . uniqid());

        // Mettre à jour le solde du compte
        $nouveauSolde = $compte->getSolde() - $montant;
        
        // Sauvegarder la transaction et mettre à jour le solde
        $this->transactionRepository->create($transaction);
        $this->compteRepository->updateSolde($compteId, $nouveauSolde);

        return true;
    }

    /**
     * Effectuer un virement entre comptes
     */
    public function transfer(int $compteSource, int $compteDestination, float $montant, string $description = ''): bool
    {
        if ($montant <= 0) {
            return false;
        }

        $compteS = $this->compteRepository->findCompteById($compteSource);
        $compteD = $this->compteRepository->findCompteById($compteDestination);

        if (!$compteS || !$compteD) {
            return false;
        }

        // Vérifier si le solde est suffisant
        if ($compteS->getSolde() < $montant) {
            return false;
        }

        $reference = 'VIR_' . time() . '_' . uniqid();

        // Transaction de débit (compte source)
        $transactionDebit = new Transaction();
        $transactionDebit->setCompteId($compteSource);
        $transactionDebit->setType('retrait'); // Type compatible avec la BD
        $transactionDebit->setMontant($montant);
        $transactionDebit->setDescription($description ?: 'Virement sortant');
        $transactionDebit->setDate(new \DateTime());
        $transactionDebit->setReference($reference . '_OUT');

        // Transaction de crédit (compte destination)
        $transactionCredit = new Transaction();
        $transactionCredit->setCompteId($compteDestination);
        $transactionCredit->setType('depot'); // Type compatible avec la BD
        $transactionCredit->setMontant($montant);
        $transactionCredit->setDescription($description ?: 'Virement entrant');
        $transactionCredit->setDate(new \DateTime());
        $transactionCredit->setReference($reference . '_IN');

        // Mettre à jour les soldes
        $nouveauSoldeSource = $compteS->getSolde() - $montant;
        $nouveauSoldeDestination = $compteD->getSolde() + $montant;

        // Sauvegarder les transactions et mettre à jour les soldes
        $this->transactionRepository->create($transactionDebit);
        $this->transactionRepository->create($transactionCredit);
        $this->compteRepository->updateSolde($compteSource, $nouveauSoldeSource);
        $this->compteRepository->updateSolde($compteDestination, $nouveauSoldeDestination);

        return true;
    }

    /**
     * Obtenir les transactions d'un compte
     */
    public function getTransactionsByCompte(int $compteId, int $limit = 50): array
    {
        return $this->transactionRepository->findByCompte($compteId, $limit);
    }

    /**
     * Obtenir les transactions d'un utilisateur
     */
    public function getTransactionsByUser(int $utilisateurId, int $limit = 50): array
    {
        return $this->transactionRepository->findByUtilisateur($utilisateurId, $limit);
    }

    /**
     * Calculer le total des transactions par type
     */
    public function getTotalByType(int $compteId, string $type): float
    {
        $transactions = $this->getTransactionsByCompte($compteId);
        $total = 0.0;

        foreach ($transactions as $transaction) {
            if ($transaction->getType() === $type) {
                $total += $transaction->getMontant();
            }
        }

        return $total;
    }

    /**
     * Obtenir les transactions récentes
     */
    public function getRecentTransactions(int $utilisateurId, int $limit = 10): array
    {
        return $this->transactionRepository->findRecentByUtilisateur($utilisateurId, $limit);
    }

    /**
     * Obtenir le solde total de tous les comptes d'un utilisateur
     */
    public function getSoldeTotal(int $utilisateurId): float
    {
        $comptes = $this->compteRepository->findByUtilisateurId($utilisateurId);
        $soldeTotal = 0.0;

        foreach ($comptes as $compte) {
            $soldeTotal += $compte->getSolde();
        }

        return $soldeTotal;
    }

    /**
     * Vérifier si un compte a suffisamment de fonds
     */
    public function hasSufficientFunds(int $compteId, float $montant): bool
    {
        $compte = $this->compteRepository->findCompteById($compteId);
        
        if (!$compte) {
            return false;
        }

        return $compte->getSolde() >= $montant;
    }

    /**
     * Obtenir l'historique des transactions avec pagination
     */
    public function getTransactionHistory(int $compteId, int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        return $this->transactionRepository->findByCompte($compteId, $perPage);
    }
}
