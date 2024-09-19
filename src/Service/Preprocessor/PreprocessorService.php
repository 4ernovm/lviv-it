<?php

namespace Chernoff\LvivItTestAssignment\Service\Preprocessor;

class PreprocessorService
{
    /**
     * @param PreprocessorPluginInterface[] $plugins
     */
    public function __construct(private readonly array $plugins)
    {}

    public function process(array $data): array
    {
        foreach ($this->plugins as $plugin) {
            if (!$plugin->supports($data)) {
                continue;
            }

            $data = $plugin->process($data);
        }

        return $data;
    }
}
