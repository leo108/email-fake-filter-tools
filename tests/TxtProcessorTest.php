<?php

namespace EmailFakeFilter\Tools\Tests;

use EmailFakeFilter\Tools\TxtProcessor;

class TxtProcessorTest extends TestCase
{
    public function testProcess(): void
    {
        $outputFolderPath = __DIR__.'/output/txt';
        $processor = new TxtProcessor(__DIR__.'/fixtures/data.txt', $outputFolderPath);
        $processor->process();
        $this->assertOutputFolder($outputFolderPath);
    }
}
