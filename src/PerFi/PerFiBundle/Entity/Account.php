<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\Entity;

use PerFi\PerFiBundle\Entity\Account;

/**
 * Account
 */
class Account
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $accountId;

    /**
     * @var string
     */
    private $title;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Set accountId
     *
     * @param string $accountId
     *
     * @return Account
     */
    public function setAccountId($accountId) : Account
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * Get accountId
     *
     * @return string
     */
    public function getAccountId() : string
    {
        return $this->accountId;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Account
     */
    public function setTitle(string $title) : Account
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }
}
