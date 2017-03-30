<?php

namespace PerFi\Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration to create the `transaction` table.
 */
class Version20170330135446 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
            'CREATE TABLE `transaction` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `transaction_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
                `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                `amount` int(11) NOT NULL,
                `currency` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
                `date` datetime NOT NULL,
                `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                `record_date` datetime NOT NULL,
                `source_account` char(36) COLLATE utf8_unicode_ci NOT NULL,
                `destination_account` char(36) COLLATE utf8_unicode_ci NOT NULL,
                `refunded` tinyint(1) NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `transaction_id` (`transaction_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE `transaction`');
    }
}
