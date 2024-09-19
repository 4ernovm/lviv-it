<?php

namespace Chernoff\LvivItTestAssignment;

use Chernoff\LvivItTestAssignment\Service\{Preprocessor,FileManager};
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container):void
    {
        $this
            ->registerConfiguration($container)
            ->registerControllers($container)
            ->registerPreprocessor($container)
            ->registerFileManager($container);
    }

    private function registerConfiguration(Container $container): self
    {
        $container['project_root'] = $_SERVER['DOCUMENT_ROOT'];

        $config = json_decode(file_get_contents('../config/config.json'), true);
        foreach ($config as $name => $value) {
            $container[$name] = $value;
        }

        return $this;
    }

    private function registerControllers(Container $container): self
    {
        $container[Controller\MainController::class] = fn($c) => new Controller\MainController(
            $c[FileManager\FileManagerService::class],
            $c[Preprocessor\PreprocessorService::class],
        );

        return $this;
    }

    private function registerPreprocessor(Container $container): self
    {
        $container[Preprocessor\Plugin\SensitiveDataRemoverPlugin::class] = fn($c)
            => new Preprocessor\Plugin\SensitiveDataRemoverPlugin($c['sensitive_data_fields']);

        // Other preprocessors to be added here...

        $container[Preprocessor\PreprocessorService::class] = function ($c) {
            $plugins = [];

            foreach ($c['preprocessor_plugins'] as $pluginName) {
                $plugins[] = $c[$pluginName];
            }

            return new Preprocessor\PreprocessorService($plugins);
        };

        return $this;
    }

    private function registerFileManager(Container $container): self
    {
        $container[FileManager\Storage\FilesystemStorage::class] = fn($c) => new FileManager\Storage\FilesystemStorage(
            $c['project_root'],
            $c['storage_config'][FileManager\Storage\FilesystemStorage::class]['path'],
        );

        $container[FileManager\FileManagerService::class] = fn($c) => new FileManager\FileManagerService($c[$c['active_storage']]);

        return $this;
    }
}
