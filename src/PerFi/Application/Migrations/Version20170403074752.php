<?php

namespace PerFi\Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration to create the `balance` table.
 */
class Version20170403074752 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
            'CREATE TABLE `balance` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `account_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
                `amount` int(11) NOT NULL,
                `currency` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `account_currency` (`account_id`, `currency`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE `balance`');
    }
}
