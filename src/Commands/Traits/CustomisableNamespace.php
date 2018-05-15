<?php

namespace Webdevjohn\Filterable\Commands\Traits;

trait CustomisableNamespace {

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        if ($this->argument('DummyNamespace')) {            
            return $this->createCustomNamespace($this->argument('DummyNamespace'));
        }        
        return $rootNamespace . $this->appendRootNamespace();
    }


    /**
     * Creates a custom namespace.
     *
     * @param string $dummyNamespace
     * @return string 
     */
    protected function createCustomNamespace(string $dummyNamespace)
    {
        $dummyNamespaceElements = explode("\\", $dummyNamespace);

        foreach ($dummyNamespaceElements as $dummyNamespaceElement) {
            $processed[] = studly_case($dummyNamespaceElement);
        }
        
        return implode($processed, '\\');
    }

    
}