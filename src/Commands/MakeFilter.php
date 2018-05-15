<?php

namespace Webdevjohn\Filterable\Commands;

use Illuminate\Console\GeneratorCommand;
use Webdevjohn\Filterable\Commands\Traits\CustomisableNamespace;

class MakeFilter extends GeneratorCommand
{
    use CustomisableNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filter:make {name} {DummyNamespace? : App/Filters}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Filter.';
    
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return realpath(__DIR__ . '/../stubs/Filter.stub');
    }

    /**
     * Append the root namespace.
     *
     * @return string
     */
    protected function appendRootNamespace()
    {
        return '\\Filters';
    }

 }