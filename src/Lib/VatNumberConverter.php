<?php

namespace Lib;

use Exception\InvalidInputException;

class VatNumberConverter
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
     * @param string $vatNumber
     *
     * @return mixed
     *
     * @throws InvalidInputException
     */
    public function convertVatNumber(string $vatNumber)
    {
        if ($vatNumber && !$this->inputValidator->validateVatNumberOption($vatNumber)) {
            throw new InvalidInputException('The only option is --vat.');
        }
        $vatNumber = explode('=', $vatNumber);

        return array_pop($vatNumber);
    }
}