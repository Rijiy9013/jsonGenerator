<?php

namespace App;

use App\Contracts\DataReaderInterface;
use Exception;

class CSVReader implements DataReaderInterface
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function read(callable $callback): void
    {
        if (!file_exists($this->filePath)) {
            throw new Exception("File not found: " . $this->filePath);
        }

        if (($handle = fopen($this->filePath, "r")) !== FALSE) {
            $header = fgetcsv($handle, 1000, ";"); // Read and discard the header
            if ($header === FALSE) {
                throw new Exception("Invalid CSV header");
            }
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $callback($data);
            }
            fclose($handle);
        } else {
            throw new Exception("Unable to open file: " . $this->filePath);
        }
    }
}
