<?php

namespace Chernoff\LvivItTestAssignment\Service\FileManager;

class FileManagerService
{
    public function __construct(
        private readonly StorageInterface $storage,
    ) {
    }

    public function save(string $name, array $content): bool
    {
        return $this->storage->save($name, $content);
    }

    public function load(string $name): ?array
    {
        return $this->storage->load($name);
    }
}
