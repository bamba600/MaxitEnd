-- Corrections de la base de données
-- Ajouter la colonne reference à la table transaction
ALTER TABLE `transaction` ADD COLUMN `reference` VARCHAR(100) DEFAULT NULL AFTER `description`;

-- Corriger l'AUTO_INCREMENT pour les tables
ALTER TABLE `compte` MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
ALTER TABLE `transaction` MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

-- Ajouter un index sur la colonne reference
ALTER TABLE `transaction` ADD INDEX `idx_transaction_reference` (`reference`);
