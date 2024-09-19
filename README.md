## Building and running the application

When starting the application for the first time you should run:

```
make init
```

This will install required dependencies.

After that application should be ready to receive requests on `http://localhost:8080/`

There are 2 available endpoints:

- `[POST] /upload/{name}` which takes the content passed via POST and saves with given name (be careful, there's no
  validation for the name uniqueness so file will be rewritten silently)
- `[GET] /download/{name}` allows you to download the content of the file

There's a Postman collection included which should make interaction process easier.

## Starting and stopping the application

To start the application use `make up`.

And to stop it you should run `make down`.

## Configuration reference:

Configuration file can be found in `config/config.json`

- `active_storage` - allows to select one of preconfigured storages (it should exist in the system and be registered in DI container)
- `storage_config` - allows to additionally configure storages
- `sensitive_data_fields` - allows to configure `SensitiveDataRemoverPlugin` and set which properties should be considered as sensitive information
- `preprocessor_plugins` - list of active preprocessor plugins

## Adding new preprocessor plugin

1. Create new plugin in `src/Service/Preprocessor/Plugin` folder. Make sure it implements `PreprocessorPluginInterface`
2. Implement interface methods.
3. Add new plugin into DI container in `\Chernoff\LvivItTestAssignment\ServiceProvider::registerPreprocessor` method
4. Add new plugin into `preprocessor_plugins` list in the configuration.

## Adding alternative file manager

1. Create new storage in `src/Service/FileManager/Storage` folder. Make sure it implements `StorageInterface`
2. Implement interface methods.
3. (Optional) Add new section under `storage_config` configuration with any additional configurations.
4. Add new storage into DI container in `\Chernoff\LvivItTestAssignment\ServiceProvider::registerFileManager` method
5. Change `active_storage` to the storage you want to use.

 