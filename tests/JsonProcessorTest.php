<?php

namespace EmailFakeFilter\Tools\Tests;

use EmailFakeFilter\Tools\JsonProcessor;

class JsonProcessorTest extends TestCase
{
    public function testProcess(): void
    {
        $outputFolderPath = __DIR__.'/output/json';
        $processor = new JsonProcessor(__DIR__.'/fixtures/json_version2.json', $outputFolderPath);
        $processor->process();
        $this->assertOutputFolder($outputFolderPath);
    }
}
