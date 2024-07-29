<?php

namespace App\Contracts;

interface DataWriterInterface {
    public function write(array $data): void;
}
