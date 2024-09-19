<?php

namespace Chernoff\LvivItTestAssignment\Service\Preprocessor;

interface PreprocessorPluginInterface
{
    public function process(array $data): array;

    public function supports(array $data): bool;
}