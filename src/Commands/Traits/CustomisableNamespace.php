<?php

namespace Webdevjohn\Filterable\Commands\Traits;

use Illuminate\Support\Str;

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
        foreach ($this->getNamespaceElements($dummyNamespace) as $dummyNamespaceElement) {
            $processed[] = Str::studly($dummyNamespaceElement);
        }
           
        return implode($processed, '\\');
    }


    /**
     * Explode the namespace and return the elements as an array.
     *
     * @param string $dummyNamespace
     * 
     * @return array
     */
    protected function getNamespaceElements(string $dummyNamespace)
    {
        if($this->stringContains($dummyNamespace, '\\')) {
            return explode("\\", $dummyNamespace);
        }

        return explode("/", $dummyNamespace);        
    }


    /**
     * Find a given needle within a string.
     *
     * @param string $theString
     * @param string $needle
     * 
     * @return boolean
     */
    protected function stringContains($theString, $needle)
    {
        return strpos($theString, $needle) !== FALSE;
    } 



}