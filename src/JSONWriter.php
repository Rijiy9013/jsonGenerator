<?php

namespace App;

use App\Contracts\DataWriterInterface;

class JSONWriter implements DataWriterInterface
{
    private string $filePath;

    /**
     * Конструктор принимает путь к JSON файлу.
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Записывает массив данных в JSON файл.
     *
     * @param array $data
     */
    public function write(array $data): void
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->filePath, $json);
    }
}
