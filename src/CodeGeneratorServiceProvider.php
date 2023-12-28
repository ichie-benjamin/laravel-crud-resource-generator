<?php

namespace IchieBenjamin\CodeGenerator;

use IchieBenjamin\CodeGenerator\Support\Helpers;
use File;
use Illuminate\Support\ServiceProvider;

class CodeGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $dir = __DIR__ . '/../';

        // publish the config base file
        $this->publishes([
            $dir . 'config/laravel-code-generator.php' => config_path('laravel-code-generator.php'),
        ], 'config');

        // publish the default-template
        $this->publishes([
            $dir . 'templates/default' => $this->codeGeneratorBase('templates/default'),
        ], 'default-template');

        // publish the defaultcollective-template
        $this->publishes([
            $dir . 'templates/default-collective' => $this->codeGeneratorBase('templates/default-collective'),
        ], 'default-collective-template');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $commands =
            [
            'IchieBenjamin\CodeGenerator\Commands\Framework\CreateControllerCommand',
            'IchieBenjamin\CodeGenerator\Commands\Framework\CreateModelCommand',
            'IchieBenjamin\CodeGenerator\Commands\Framework\CreateLanguageCommand',
            'IchieBenjamin\CodeGenerator\Commands\Framework\CreateFormRequestCommand',
            'IchieBenjamin\CodeGenerator\Commands\Framework\CreateRoutesCommand',
            'IchieBenjamin\CodeGenerator\Commands\Framework\CreateMigrationCommand',
            'IchieBenjamin\CodeGenerator\Commands\Framework\CreateScaffoldCommand',
            'IchieBenjamin\CodeGenerator\Commands\Framework\CreateResourcesCommand',
            'IchieBenjamin\CodeGenerator\Commands\Framework\CreateMappedResourcesCommand',
            'IchieBenjamin\CodeGenerator\Commands\Resources\ResourceFileFromDatabaseCommand',
            'IchieBenjamin\CodeGenerator\Commands\Resources\ResourceFileCreateCommand',
            'IchieBenjamin\CodeGenerator\Commands\Resources\ResourceFileDeleteCommand',
            'IchieBenjamin\CodeGenerator\Commands\Resources\ResourceFileAppendCommand',
            'IchieBenjamin\CodeGenerator\Commands\Resources\ResourceFileReduceCommand',
            'IchieBenjamin\CodeGenerator\Commands\Views\CreateIndexViewCommand',
            'IchieBenjamin\CodeGenerator\Commands\Views\CreateCreateViewCommand',
            'IchieBenjamin\CodeGenerator\Commands\Views\CreateFormViewCommand',
            'IchieBenjamin\CodeGenerator\Commands\Views\CreateEditViewCommand',
            'IchieBenjamin\CodeGenerator\Commands\Views\CreateShowViewCommand',
            'IchieBenjamin\CodeGenerator\Commands\Views\CreateViewsCommand',
            'IchieBenjamin\CodeGenerator\Commands\Views\CreateViewLayoutCommand',
            'IchieBenjamin\CodeGenerator\Commands\Views\CreateLayoutCommand',
            'IchieBenjamin\CodeGenerator\Commands\Api\CreateApiControllerCommand',
            'IchieBenjamin\CodeGenerator\Commands\Api\CreateApiScaffoldCommand',
            'IchieBenjamin\CodeGenerator\Commands\ApiDocs\CreateApiDocsControllerCommand',
            'IchieBenjamin\CodeGenerator\Commands\ApiDocs\CreateApiDocsScaffoldCommand',
            'IchieBenjamin\CodeGenerator\Commands\ApiDocs\CreateApiDocsViewCommand',
                'IchieBenjamin\CodeGenerator\Commands\Resources\ResourceFileFromDatabaseAllCommand'
        ];

        if (Helpers::isNewerThanOrEqualTo()) {
            $commands = array_merge($commands,
                [
                    'IchieBenjamin\CodeGenerator\Commands\Migrations\MigrateAllCommand',
                    'IchieBenjamin\CodeGenerator\Commands\Migrations\RefreshAllCommand',
                    'IchieBenjamin\CodeGenerator\Commands\Migrations\ResetAllCommand',
                    'IchieBenjamin\CodeGenerator\Commands\Migrations\RollbackAllCommand',
                    'IchieBenjamin\CodeGenerator\Commands\Migrations\StatusAllCommand',
                ]);
        }

        if (Helpers::isApiResourceSupported()) {
            $commands = array_merge($commands,
                [
                    'IchieBenjamin\CodeGenerator\Commands\Api\CreateApiResourceCommand',
                ]);
        }

        $this->commands($commands);
    }

    /**
     * Create a directory if one does not already exists
     *
     * @param string $path
     *
     * @return void
     */
    protected function createDirectory($path)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true);
        }
    }

    /**
     * Get the laravel-code-generator base path
     *
     * @param string $path
     *
     * @return string
     */
    protected function codeGeneratorBase($path = null)
    {
        return base_path('resources/laravel-code-generator/') . $path;
    }
}
