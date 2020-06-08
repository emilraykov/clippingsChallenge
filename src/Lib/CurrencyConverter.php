<?php

namespace Lib;

use Entity\Currency;
use Exception\InvalidInputException;

class CurrencyConverter
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
     * @param $currenciesData
     *
     * @return array
     *
     * @throws InvalidInputException
     */
    public function convertCurrencies($currenciesData)
    {
        $currencies = [];
        $currenciesData = explode(',', $currenciesData);

        foreach ($currenciesData as $currency) {
            $currency = explode(':', $currency);
            $isValid = $this->inputValidator->validateInputCurrencyData($currency);
            if (!$isValid) {
                throw new InvalidInputException(
                    'Sorry, the supported currencies are ' . implode(', ', Currency::SUPPORTED_CURRENCIES)
                );
            }

            $currencies[] = new Currency($currency[0], $currency[1]);
        }

        return $currencies;
    }

    /**
     * @param string $currency
     *
     * @return string
     *
     * @throws InvalidInputException
     */
    public function convertDisplayedCurrency(string $currency)
    {
        if (!$this->inputValidator->validateCurrency($currency)) {
            throw new InvalidInputException(
                'Sorry, the supported currencies are ' . implode(', ', Currency::SUPPORTED_CURRENCIES)
            );
        }

        return $currency;
    }
}