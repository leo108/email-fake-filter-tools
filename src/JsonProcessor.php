<?php

namespace EmailFakeFilter\Tools;

class JsonProcessor extends Processor
{
    protected function loadData(): void
    {
        $raw = \Safe\json_decode(\Safe\file_get_contents($this->inputFilePath), true);

        if (! is_array($raw) || ! array_key_exists('domains', $raw)) {
            throw new \InvalidArgumentException('Input file does not contain "domains" key');
        }

        foreach ($raw['domains'] as $domain => $info) {
            $this->results[self::calculateHash($domain)][$domain] = $info;
            unset($raw['domains'][$domain]);
        }
    }

    protected function convertToOutputFileContent(array $data): string
    {
        return \Safe\json_encode($data);
    }
}
