<?php

namespace Lib;

use Entity\Currency;
use Entity\Invoice;

class InputValidator
{
    const MIN_STRING_LEN = 3;

    /**
     * @param array $invoicesData
     *
     * @return bool
     */
    public function validateInputInvoiceData(array $invoicesData)
    {
        if (
            count($invoicesData) != 7
            || mb_strlen($invoicesData[0]) < self::MIN_STRING_LEN
            || !ctype_digit($invoicesData[1])
            || !ctype_digit($invoicesData[2])
            || !ctype_digit($invoicesData[3])
            || !in_array($invoicesData[3], array_keys(Invoice::INVOICE_TYPES))
            || (!ctype_digit($invoicesData[4]) && !empty($invoicesData[4]))
            || !$this->validateCurrency($invoicesData[5])
            || !is_numeric($invoicesData[6])
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param $currencyData
     *
     * @return bool
     */
    public function validateInputCurrencyData($currencyData)
    {
        if (
            count($currencyData) != 2
            || !$this->validateCurrency($currencyData[0])
            || !is_numeric($currencyData[1])
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param string $currency
     *
     * @return bool
     */
    public function validateCurrency(string $currency)
    {
        return in_array($currency, Currency::SUPPORTED_CURRENCIES);
    }

    /**
     * @param string $vatNumber
     *
     * @return false|int
     */
    public function validateVatNumberOption(string $vatNumber)
    {
        return preg_match("/--vat=\d+/", $vatNumber);
    }
}