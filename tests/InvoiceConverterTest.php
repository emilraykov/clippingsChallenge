<?php


use Lib\InvoiceConverter;
use PHPUnit\Framework\TestCase;

class InvoiceConverterTest extends TestCase
{
    public function testInvoiceConverter()
    {
        $inputValidator = new \Lib\InputValidator();
        $invoiceConverter = new InvoiceConverter($inputValidator);

        $this->expectException(\Exception\InvalidInputException::class);
        $invoiceConverter->convertInvoices([['not'=>'correct'],['not' => 'correct']]);
    }
}
