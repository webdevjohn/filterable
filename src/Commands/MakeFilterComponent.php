<?php

namespace Webdevjohn\Filterable\Commands;

use Illuminate\Console\GeneratorCommand;
use Webdevjohn\Filterable\Commands\Traits\CustomisableNamespace;

class MakeFilterComponent extends GeneratorCommand
{
    use CustomisableNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filter:component {name} {DummyNamespace? : App/Filters/Components}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Filter Component.';
    
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return realpath(__DIR__ . '/../stubs/FilterComponent.stub');
    }

    /**
     * Append the root namespace.
     *
     * @return string
     */
    protected function appendRootNamespace()
    {
        return '\\Models\\Filters\\_Components';
    }
   
}