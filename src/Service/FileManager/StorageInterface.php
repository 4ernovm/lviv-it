<?php

namespace Chernoff\LvivItTestAssignment\Service\FileManager;

interface StorageInterface
{
    public function save(string $name, array $content): bool;

    public function load(string $name): ?array;
}
