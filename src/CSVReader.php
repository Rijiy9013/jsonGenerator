<?php

namespace App;

use App\Contracts\DataReaderInterface;
use Exception;

class CSVReader implements DataReaderInterface
{
    private string $filePath;

    /**
     * Конструктор принимает путь к CSV файлу.
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Читает CSV файл построчно и передает каждую строку в callback функцию.
     *
     * @param callable $callback
     * @throws Exception
     */
    public function read(callable $callback): void
    {
        if (!file_exists($this->filePath)) {
            throw new Exception("File not found: " . $this->filePath); // Можно создать кастомный exception
        }

        if (($handle = fopen($this->filePath, "r"))) {
            $header = fgetcsv($handle, 1000, ";");
            if (!$header) {
                throw new Exception("Invalid CSV header"); // Можно создать кастомный exception
            }
            while (($data = fgetcsv($handle, 1000, ";"))) {
                $callback($data);
            }
            fclose($handle);
        } else {
            throw new Exception("Unable to open file: " . $this->filePath); // Можно создать кастомный exception
        }
    }
}
