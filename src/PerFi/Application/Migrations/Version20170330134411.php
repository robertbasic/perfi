<?php

namespace PerFi\Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration to create the `account` table.
 */
class Version20170330134411 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
            'CREATE TABLE `account` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `account_id` char(36) COLLATE utf8_unicode_ci NOT NULL,
                `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `account_id` (`account_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE `account`');
    }
}
