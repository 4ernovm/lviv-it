<?php

namespace Chernoff\LvivItTestAssignment\Service\StreamDataReader;

class StreamDataReaderService
{
    public function read(): string
    {
        $input = fopen('php://input', 'r');
        return stream_get_contents($input);
    }
}
