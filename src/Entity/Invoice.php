<?php

namespace Entity;

class Invoice
{
    const INVOICE_TYPE = 1;
    const CREDIT_NOTE_TYPE = 2;
    const DEBIT_NOTE_TYPE = 3;
    const INVOICE_TYPES = [
        self::INVOICE_TYPE => 'Invoice',
        self::CREDIT_NOTE_TYPE => 'Credit note',
        self::DEBIT_NOTE_TYPE => 'Debit note'
    ];

    /**
     * @var string
     */
    private $customer;

    /**
     * @var int
     */
    private $vatNumber;

    /**
     * @var int
     */
    private $documentNumber;

    /**
     * @var int
     */
    private $type;

    /**
     * @var int
     */
    private $parentDocument;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var float
     */
    private $sum;

    /**
     * Invoice constructor.
     *
     * @param string $customer
     * @param int $vatNumber
     * @param int $documentNumber
     * @param int $type
     * @param string $currency
     * @param float $sum
     * @param ?int $parentDocument
     */
    public function __construct(
        $customer,
        $vatNumber,
        $documentNumber,
        $type,
        $currency,
        $sum,
        $parentDocument = null
    ) {
        $this->customer = $customer;
        $this->vatNumber = $vatNumber;
        $this->documentNumber = $documentNumber;
        $this->type = $type;
        $this->currency = $currency;
        $this->sum = $sum;
        $this->parentDocument = $parentDocument;
    }

    /**
     * @return string
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @return int
     */
    public function getVatNumber()
    {
        return $this->vatNumber;
    }

    /**
     * @return int
     */
    public function getDocumentNumber()
    {
        return $this->documentNumber;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getParentDocument()
    {
        return $this->parentDocument;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return float
     */
    public function getSum()
    {
        return $this->sum;
    }
}
