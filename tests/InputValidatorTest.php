<?php


use Lib\InputValidator;
use PHPUnit\Framework\TestCase;

class InputValidatorTest extends TestCase
{
    private $inputValidator;

    public function setUp(): void
    {
        $this->inputValidator = new InputValidator();
    }

    public function testInvoiceValidator()
    {
        $this->assertFalse(false, $this->inputValidator->validateInputInvoiceData([]));
    }

    public function testInputCurrencyData()
    {
        $this->assertTrue(true, $this->inputValidator->validateInputCurrencyData(['EUR', 2]));
    }

    public function testCurrencies()
    {
        $this->assertTrue(true, $this->inputValidator->validateCurrency('EUR'));
        $this->assertTrue(true, $this->inputValidator->validateCurrency('GBP'));
        $this->assertTrue(true, $this->inputValidator->validateCurrency('USD'));
        $this->assertFalse(false, $this->inputValidator->validateCurrency('BGN'));
    }

    public function testInputVatParameter()
    {
        $this->assertTrue(true, $this->inputValidator->validateVatNumberOption('--vat=123'));
        $this->assertFalse(false, $this->inputValidator->validateVatNumberOption('--vat=dada'));
    }
}
