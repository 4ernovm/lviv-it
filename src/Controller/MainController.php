<?php

namespace Chernoff\LvivItTestAssignment\Controller;

use Chernoff\LvivItTestAssignment\Service\FileManager\FileManagerService;
use Chernoff\LvivItTestAssignment\Service\Preprocessor\PreprocessorService;
use Chernoff\LvivItTestAssignment\Service\StreamDataReader\StreamDataReaderService;

class MainController
{
    public function __construct(
        private readonly FileManagerService $fileManager,
        private readonly PreprocessorService $preprocessor,
        private readonly StreamDataReaderService $reader,
    ) {
    }

    public function upload(string $name): array
    {
        $content = $this->reader->read();
        $content = json_decode($content, true);

        if (empty($content['source']) || empty($content['payload'])) {
            return ['status' => 400, 'error' => '"source" and "payload" are required'];
        }

        if (!is_array($content['payload'])) {
            return ['status' => 400, 'error' => '"payload" should be an array'];
        }

        $content = $this->preprocessor->process($content, true);
        $isSaved = $this->fileManager->save($name, $content);

        return ['status' => 200, 'is_successful' => $isSaved];
    }

    public function download(string $name): array
    {
        $content = $this->fileManager->load($name);

        return $content ?? ['status' => 404];
    }
}
