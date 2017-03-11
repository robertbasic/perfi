<?php

namespace PerFi\PerFiBundle\Entity;

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
     * @var guid
     */
    private $accountId;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set accountId
     *
     * @param guid $accountId
     *
     * @return Account
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * Get accountId
     *
     * @return guid
     */
    public function getAccountId()
    {
        return $this->accountId;
    }
}

