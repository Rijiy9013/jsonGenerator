<?php

use PHPUnit\Framework\TestCase;

class TreeGeneratorTest extends TestCase
{
    private $inputFile = __DIR__ . '/../input.csv';
    private $outputFile = __DIR__ . '/../output.json';
    private $expectedOutputFile = __DIR__ . '/../output-true.json';

    public function testTreeGeneration()
    {
        // Run the gentree.php script
        $command = "php " . __DIR__ . "/../gentree.php $this->inputFile $this->outputFile";
        exec($command, $output, $returnVar);

        // Check if the script executed successfully
        $this->assertEquals(0, $returnVar, "gentree.php script failed to run.");

        // Read the generated JSON file
        $generatedJson = file_get_contents($this->outputFile);
        $this->assertNotFalse($generatedJson, "Failed to read generated output.json file.");

        // Read the expected JSON file
        $expectedJson = file_get_contents($this->expectedOutputFile);
        $this->assertNotFalse($expectedJson, "Failed to read expected output-true.json file.");

        // Compare the generated JSON with the expected JSON
        $this->assertJsonStringEqualsJsonString($expectedJson, $generatedJson, "The generated JSON does not match the expected JSON.");
    }
}
