<?php

namespace App;

use Entity\Currency;
use Entity\Invoice;
use Exception\InvalidInputException;

class App
{
    private $invoices;
    private $currencies;
    private $displayedCurrency;
    private $vatNumber;

    /**
     * @param array $invoices
     */
    public function setData(array $invoices): void
    {
        $this->invoices = $invoices;
    }

    /**
     * @param array $currencies
     */
    public function setCurrencies(array $currencies): void
    {
        $this->currencies = $currencies;
    }

    /**
     * @param string $currency
     */
    public function setDisplayedCurrency(string $currency): void
    {
        $this->displayedCurrency = $currency;
    }

    /**
     * @param $vatNumber
     */
    public function setVatNumber($vatNumber)
    {
        $this->vatNumber = $vatNumber;
    }

    /**
     * @return array
     *
     * @throws InvalidInputException
     */
    public function sumTotals(): array
    {
        if ($this->invoices == null || $this->currencies == null || $this->displayedCurrency == null) {
            throw new InvalidInputException('Invoices, currencies or displayed currency are not set.');
        }

        $this->checkForNegativeInvoices();
        $defaultCurrency = $this->getDefaultCurrency();
        $sumByClients = $this->sumInvoicesByClients($defaultCurrency);

        if ($this->displayedCurrency != $defaultCurrency->getName()) {
            $sumByClients = $this->convertSumByDisplayedCurrency($sumByClients);
        }

        return $sumByClients;
    }

    /**
     * @return Currency
     *
     * @throws InvalidInputException
     */
    protected function getDefaultCurrency()
    {
        foreach ($this->currencies as $currency) {
            if ($currency->getRate() == 1) {
                return $currency;
            }
        }

        throw new InvalidInputException('No default currency specified.');
    }

    /**
     * @param string $currencyName
     *
     * @return float
     *
     * @throws InvalidInputException
     */
    protected function getExchangeRate(string $currencyName)
    {
        foreach ($this->currencies as $currency) {
            if ($currency->getName() == $currencyName) {
                return $currency->getRate();
            }
        }

        throw new InvalidInputException('Exchange rate for some of the invoices is not specified.');
    }

    /**
     * @param Currency $defaultCurrency
     *
     * @return array
     *
     * @throws InvalidInputException
     */
    protected function sumInvoicesByClients(Currency $defaultCurrency)
    {
        $invoicesSum = [];

        foreach ($this->invoices as $invoice) {
            if (!isset($invoicesSum[$invoice->getVatNumber()])) {
                if ($this->vatNumber && $this->vatNumber != $invoice->getVatNumber()) {
                    continue;
                }
                $invoicesSum[$invoice->getVatNumber()] = [
                    'customerName' => $invoice->getCustomer(),
                    'total' => 0,
                    'currency' => $defaultCurrency->getName()
                ];
            }
        }

        foreach ($this->invoices as $invoice) {
            if ($this->vatNumber && $this->vatNumber != $invoice->getVatNumber()) {
                continue;
            }

            if ($invoice->getType() != Invoice::CREDIT_NOTE_TYPE) {
                $amountToAdd = $invoice->getSum();
                if ($invoice->getCurrency() != $defaultCurrency) {
                    $amountToAdd = $amountToAdd * $this->getExchangeRate($invoice->getCurrency());
                }

                $invoicesSum[$invoice->getVatNumber()]['total'] = round(
                    $invoicesSum[$invoice->getVatNumber()]['total'] + $amountToAdd,
                    2
                );
            } else {
                $amountToSubtract = $invoice->getSum();
                if ($invoice->getCurrency() != $defaultCurrency) {
                    $amountToSubtract = $amountToSubtract * $this->getExchangeRate($invoice->getCurrency());
                }

                $invoicesSum[$invoice->getVatNumber()]['total'] = round(
                    $invoicesSum[$invoice->getVatNumber()]['total'] - $amountToSubtract,
                    2
                );
            }
        }

        return $invoicesSum;
    }

    /**
     * @param array $sumByClients
     *
     * @return array
     *
     * @throws InvalidInputException
     */
    protected function convertSumByDisplayedCurrency(array $sumByClients)
    {
        foreach ($sumByClients as &$sumByClient) {
            $sumByClient['total'] = round(
                $sumByClient['total'] * $this->getExchangeRate($this->displayedCurrency),
                2
            );
            $sumByClient['currency'] = $this->displayedCurrency;
        }

        return $sumByClients;
    }

    /**
     * @throws InvalidInputException
     */
    protected function checkForNegativeInvoices()
    {
        foreach ($this->invoices as $invoice) {
            if ($invoice->getType() == Invoice::INVOICE_TYPE) {
                $sum = 0;
                $documentSum = $invoice->getSum();
                if ($invoice->getCurrency() != $this->getDefaultCurrency()->getName()) {
                    $documentSum = round(
                        $invoice->getSum() * $this->getExchangeRate($invoice->getCurrency()),
                        2
                    );
                }

                foreach ($this->invoices as $invoiceB) {
                    if (
                        $invoiceB->getType() == Invoice::CREDIT_NOTE_TYPE
                        && $invoiceB->getParentDocument() == $invoice->getDocumentNumber()
                    ) {
                        if ($invoiceB->getCurrency() != $this->getDefaultCurrency()->getName()) {
                            $sum += round($invoiceB->getSum() * $this->getExchangeRate($invoiceB->getCurrency()));
                        } else {
                            $sum += $invoiceB->getSum();
                        }
                    }
                }

                if ($sum > $documentSum) {
                    throw new InvalidInputException(
                        'Document number ' . $invoice->getDocumentNumber() . ' has more credit notes sum than the invoice.'
                    );
                }
            }
        }
    }
}
