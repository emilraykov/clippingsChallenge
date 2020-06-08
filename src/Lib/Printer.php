<?php

namespace Lib;

class Printer
{
    /**
     * @param array $array
     */
    public function printArray(array $array): void
    {
        foreach ($array as $item) {
            echo $item['customerName'] . ' - ' . $item['total'] . ' ' . $item['currency'] . PHP_EOL;
        }
    }
}