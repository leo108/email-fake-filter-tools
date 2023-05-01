<?php

namespace EmailFakeFilter\Tools\Tests;

use PHPUnit\Framework\TestCase as Base;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Spatie\Snapshots\MatchesSnapshots;

class TestCase extends Base
{
    use MatchesSnapshots;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function assertOutputFolder(string $outputFolder): void
    {
        $outputFolder = rtrim($outputFolder, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        $results = [];

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($outputFolder, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);

        /** @var \RecursiveDirectoryIterator $file */
        foreach ($files as $file) {
            if ($file->isFile()) {
                $path = $file->getRealPath();

                if (substr($path, 0, strlen($outputFolder)) == $outputFolder) {
                    $path = substr($path, strlen($outputFolder));
                }

                $results[$path] = \Safe\file_get_contents($file->getRealPath());
            }
        }

        $this->assertMatchesJsonSnapshot($results);
    }
}
