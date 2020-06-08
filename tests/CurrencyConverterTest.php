<?php

use Lib\CurrencyConverter;
use PHPUnit\Framework\TestCase;

class CurrencyConverterTest extends TestCase
{
    private $inputValidator;
    private $currencyConverter;

    public function setUp(): void
    {
        $this->inputValidator = new \Lib\InputValidator();
        $this->currencyConverter = new CurrencyConverter($this->inputValidator);
    }

    public function testConvertCurrencies()
    {
        $this->expectException(\Exception\InvalidInputException::class);
        $this->currencyConverter->convertCurrencies('BGN:2,test:2,wontwork:1');
    }

    public function testDisplayCurrency()
    {
        $this->assertSame('EUR', $this->currencyConverter->convertDisplayedCurrency('EUR'));

        $this->expectException(\Exception\InvalidInputException::class);

        $this->currencyConverter->convertDisplayedCurrency('BGN');
    }
}
