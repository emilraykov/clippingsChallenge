<?php


use Lib\CsvReader;
use PHPUnit\Framework\TestCase;

class CsvReaderTest extends TestCase
{
    public function testRead()
    {
        $csvReader = new CsvReader();
        $this->expectException(\Exception\InvalidInputException::class);
        $csvReader->read('test');
    }
}
