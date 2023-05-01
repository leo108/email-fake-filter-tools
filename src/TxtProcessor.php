<?php

namespace EmailFakeFilter\Tools;

class TxtProcessor extends Processor
{
    protected function loadData(): void
    {
        $fh = \Safe\fopen($this->inputFilePath, 'r');

        while (($line = fgets($fh)) !== false) {
            // trim off the trailing newline
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            $this->results[self::calculateHash($line)][] = $line;
        }

        \Safe\fclose($fh);
    }

    protected function convertToOutputFileContent(array $data): string
    {
        return implode("\n", $data);
    }
}
