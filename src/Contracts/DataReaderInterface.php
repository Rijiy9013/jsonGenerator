<?php

namespace App\Contracts;

interface DataReaderInterface
{
    public function read(callable $callback): void;
}
