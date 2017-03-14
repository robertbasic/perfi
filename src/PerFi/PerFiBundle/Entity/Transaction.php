<?php

namespace PerFi\PerFiBundle\Entity;

/**
 * Transaction
 */
class Transaction
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $transactionId;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $sourceAccount;

    /**
     * @var string
     */
    private $destinationAccount;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $description;

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
     * Set transactionId
     *
     * @param string $transactionId
     *
     * @return Transaction
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Transaction
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set sourceAccount
     *
     * @param string $sourceAccount
     *
     * @return Transaction
     */
    public function setSourceAccount($sourceAccount)
    {
        $this->sourceAccount = $sourceAccount;

        return $this;
    }

    /**
     * Get sourceAccount
     *
     * @return string
     */
    public function getSourceAccount()
    {
        return $this->sourceAccount;
    }

    /**
     * Set destinationAccount
     *
     * @param string $destinationAccount
     *
     * @return Transaction
     */
    public function setDestinationAccount($destinationAccount)
    {
        $this->destinationAccount = $destinationAccount;

        return $this;
    }

    /**
     * Get destinationAccount
     *
     * @return string
     */
    public function getDestinationAccount()
    {
        return $this->destinationAccount;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return Transaction
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Transaction
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Transaction
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
