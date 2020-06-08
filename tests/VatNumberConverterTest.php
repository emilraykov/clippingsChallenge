<?php


use Lib\VatNumberConverter;
use PHPUnit\Framework\TestCase;

class VatNumberConverterTest extends TestCase
{
    public function testInvoiceConverter()
    {
        $inputValidator = new \Lib\InputValidator();
        $vatNumberConverter = new VatNumberConverter($inputValidator);

        $this->expectException(\Exception\InvalidInputException::class);
        $vatNumberConverter->convertVatNumber('--vat=test');
    }
}
