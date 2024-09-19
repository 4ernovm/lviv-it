<?php

namespace Chernoff\LvivItTestAssignment\Service\Preprocessor\Plugin;

use Chernoff\LvivItTestAssignment\Service\Preprocessor\PreprocessorPluginInterface;

class SensitiveDataRemoverPlugin implements PreprocessorPluginInterface
{
    private const REPLACEMENT = '_SENSITIVE_DATA_REMOVED_';

    public function __construct(private readonly array $sensitiveDataKeys)
    {}

    public function process(array $data): array
    {
        foreach ($this->sensitiveDataKeys as $sensitiveDataKey) {
            if (empty($data['payload'][$sensitiveDataKey])) {
                continue;
            }

            $data['payload'][$sensitiveDataKey] = self::REPLACEMENT;
        }

        return $data;
    }

    public function supports(array $data): bool
    {
        return !empty($data['payload']);
    }
}
