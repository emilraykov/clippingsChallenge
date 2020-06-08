<?php

namespace Lib;

use Exception\InvalidInputException;

class CsvReader implements ReaderInterface
{
    /**
     * @param string $filePath
     *
     * @return array
     *
     * @throws InvalidInputException
     */
    public function read($filePath)
    {
        if (!file_exists($filePath)) {
            throw new InvalidInputException('File ' . $filePath . ' doesnt exists!');
        }

        return array_map('str_getcsv', file($filePath));
    }
}