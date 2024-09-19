<?php

namespace Chernoff\LvivItTestAssignment\Controller;

use Chernoff\LvivItTestAssignment\Service\FileManager\FileManagerService;
use Chernoff\LvivItTestAssignment\Service\Preprocessor\PreprocessorService;

class MainController
{
    public function __construct(
        private readonly FileManagerService $fileManager,
        private readonly PreprocessorService $preprocessor,
    ) {
    }

    public function upload(string $name): array
    {
        $input = fopen('php://input', 'r');
        $content = json_decode(stream_get_contents($input), true);

        if (empty($content['source']) || empty($content['payload'])) {
            return ['status' => 400, 'error' => '"source" and "payload" are required'];
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
