<?php

namespace EmailFakeFilter\Tools;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

abstract class Processor
{
    /**
     * @var array<string,array<mixed>>
     */
    protected array $results = [];

    protected readonly string $outputFileExtension;

    public function __construct(public readonly string $inputFilePath, public readonly string $outputFolderPath)
    {
        if (! file_exists($this->inputFilePath)) {
            throw new \InvalidArgumentException('Input file does not exist');
        }

        if (is_file($this->outputFolderPath)) {
            throw new \InvalidArgumentException('Output folder path is a file');
        }

        if (is_dir($this->outputFolderPath)) {
            self::rmdirRecursive($this->outputFolderPath);
        }

        \Safe\mkdir($this->outputFolderPath, 0777, true);

        $this->outputFileExtension = pathinfo($this->inputFilePath, PATHINFO_EXTENSION);

        if ($this->outputFileExtension === '') {
            throw new \InvalidArgumentException('Input file does not have an extension');
        }
    }

    abstract protected function loadData(): void;

    /**
     * @param  array<mixed>  $data
     */
    abstract protected function convertToOutputFileContent(array $data): string;

    final protected function calculateHash(string $domain): string
    {
        return substr(md5($domain), 0, 2);
    }

    final public function process(): void
    {
        $this->loadData();

        foreach ($this->results as $hash => $data) {
            \Safe\file_put_contents($this->outputFolderPath.DIRECTORY_SEPARATOR.$hash.'.'.$this->outputFileExtension, static::convertToOutputFileContent($data));
        }
    }

    protected static function rmdirRecursive(string $path): void
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);

        /** @var RecursiveDirectoryIterator $file */
        foreach ($files as $file) {
            if ($file->isDir()) {
                \Safe\rmdir($file->getRealPath());
            } else {
                \Safe\unlink($file->getRealPath());
            }
        }

        \Safe\rmdir($path);
    }
}
