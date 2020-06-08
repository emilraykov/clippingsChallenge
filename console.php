#!/usr/bin/php
<?php

use App\App;
use Exception\ExceptionHandler;
use Lib\CurrencyConverter;
use Lib\CsvReader;
use Lib\InputValidator;
use Lib\InvoiceConverter;
use Lib\Printer;
use Lib\VatNumberConverter;

require_once __DIR__ . '/vendor/autoload.php';
$exceptionHandler = new ExceptionHandler();
$exceptionHandler->setExceptionHandler();

$inputValidator = new InputValidator();
$fileReader = new CsvReader();
$invoiceConverter = new InvoiceConverter($inputValidator);
$currencyConverter = new CurrencyConverter($inputValidator);
$vatNumberConverter = new VatNumberConverter($inputValidator);

$csvData = $fileReader->read($argv[2]);
$invoices = $invoiceConverter->convertInvoices($csvData);
$currencies = $currencyConverter->convertCurrencies($argv[3]);
$displayedCurrency = $currencyConverter->convertDisplayedCurrency($argv[4]);
$vatNumber = $vatNumberConverter->convertVatNumber($argv[5] ?? 0);

$app = new App();
$app->setData($invoices);
$app->setCurrencies($currencies);
$app->setDisplayedCurrency($displayedCurrency);
$app->setVatNumber($vatNumber);
$output = $app->sumTotals();

$printer = new Printer();
$printer->printArray($output);