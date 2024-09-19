<?php

namespace Chernoff\LvivItTestAssignment\Service\FileManager\Storage;

use Chernoff\LvivItTestAssignment\Service\FileManager\StorageInterface;

class FilesystemStorage implements StorageInterface
{
    public function __construct(
        private readonly string $rootDir,
        private readonly string $path,
    ) {
    }

    public function save(string $name, array $content): bool
    {
        return (bool) file_put_contents(
            $this->getFullPath($name),
            json_encode($content),
        );
    }

    public function load(string $name): ?array
    {
        $content = (string) file_get_contents($this->getFullPath($name));

        return json_decode($content, true);
    }

    private function getFullPath(string $name): string
    {
        return implode('/', [$this->rootDir, '..', $this->path, $name]) . '.json';
    }
}
