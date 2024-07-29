<?php

require 'vendor/autoload.php';

use App\CSVReader;
use App\JSONWriter;
use App\TreeBuilder;

class CLI
{
    public static function run(array $argv): void
    {
        if (count($argv) != 3) {
            echo "Usage: php gentree.php input.csv output.json\n";
            exit(1);
        }

        $inputFile = $argv[1];
        $outputFile = $argv[2];

        $reader = new CSVReader($inputFile);
        $writer = new JSONWriter($outputFile);
        $builder = new TreeBuilder($reader, $writer);

        $builder->buildTree();

        echo "Tree successfully generated and saved to $outputFile\n";
    }
}

CLI::run($argv);
