<?php

namespace Lib;

use Entity\Invoice;
use Exception\InvalidInputException;

class InvoiceConverter
{
    /**
     * @var InputValidator
     */
    private $inputValidator;

    public function __construct(InputValidator $inputValidator)
    {
        $this->inputValidator = $inputValidator;
    }

    /**
     * @param array $data
     *
     * @return array
     *
     * @throws InvalidInputException
     */
    public function convertInvoices(array $data)
    {
        $invoices = [];
        array_shift($data);
        foreach ($data as $item) {
            $isValid = $this->inputValidator->validateInputInvoiceData($item);
            if (!$isValid) {
                throw new InvalidInputException('Sorry, some of the fields in the cvs file is not correct.');
            }

            list($customer, $vatNumber, $documentNumber, $type, $parentDocument, $currency, $sum) = $item;
            if (
                !empty($parentDocument)
                && array_search($parentDocument, array_column($data, 2)) === false
            ) {
                throw new InvalidInputException('The parent document ' . $parentDocument . ' is missing.');
            } elseif ($type != 1 && empty($parentDocument)) {
                throw new InvalidInputException('Types debit and credit note must have parent document.');
            }

            $invoices[] = new Invoice($customer, $vatNumber, $documentNumber, $type, $currency, $sum, $parentDocument);
        }

        return $invoices;
    }

}