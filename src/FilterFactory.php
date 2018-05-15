<?php

namespace Webdevjohn\Filterable;

use Webdevjohn\Filterable\Interfaces\FilterComponentInterface;

class FilterFactory {

	/**
	 * The filter component implementation.
	 *
	 * @var FilterComponentInterface
	 */
	protected $component;
	
	/**
	 * Create a new FilterFactory.
	 *
	 * @param FilterComponentInterface $component
	 */
	public function __construct(FilterComponentInterface $component)
	{
		$this->component = $component;		
	}


	/**
	 * Make the filters.
	 *
	 * @param $query
	 * @param array $requestInput
	 * 
	 * @return void
	 */
	public function make($query, array $requestInput)
	{
		$this->applyFilters($query, $requestInput);
		$this->applyCommonFilters($query, $requestInput);
	}

	
    /**
	 * Dynamically applies multiple filters to the model.
	 *
	 * @param $query
	 * @param array $requestInput 
	 * 
	 * @return void
	 */
    protected function applyFilters($query, array $requestInput)
    {       	
		foreach ($this->appliableFilters($requestInput, $this->component->getInstantiableFilters()) as $requestInputName) 
		{		
			$this->makeFilterFrom($requestInputName)->filter($query, $requestInput[$requestInputName]);
		}	 		 
    }


	/**
	 * Dynamically applies multiple common filters to the model.
	 *
	 * @param $query
	 * @param array $requestInput 
	 * 
	 * @return void
	 */
	protected function applyCommonFilters($query, array $requestInput)
	{
		foreach ($this->appliableFilters($requestInput, $this->component->getInstantiableCommonFilters()) as $requestInputName) 
		{		
			$this->makeFilterFrom($requestInputName, true)->filter($query, $requestInput[$requestInputName]);
		}	
	}


    /**
	 * Compares the Request Input with $instantiableFilters, returning 
	 * an array of appliable filters to be instantiated.
	 *
	 * @param array $requestInput
	 * @param array $instantiableFilters
	 * 
	 * @return array 
	 */
	protected function appliableFilters(array $requestInput, array $instantiableFilters)
	{
		return array_intersect(
			array_keys($requestInput), $instantiableFilters 
		);
    }
	

	/**
	 * Returns an instantiated filter class.
	 *
	 * @param string $className
	 * @param bool $commonFilterFlag
	 * 
	 * @return Class  
	 */
	protected function makeFilterFrom(string $className, bool $commonFilterFlag = false)
	{	
		$class = $this->resolveNamespace($className, $commonFilterFlag);

		return new $class;
	}


	/**
	 * Determines which namespace to prepend to the classname.
	 * Returns a fully qualified classname.
	 * 
	 * @param string $className
	 * @param bool $commonFilterFlag
	 * 
	 * @return string 
	 */
	protected function resolveNamespace(string $className, bool $commonFilterFlag)
	{
		$className = studly_case($className);

		$className .= 'Filter';

		if ($commonFilterFlag) return $this->component->getInstantiableCommonFiltersNamespace() . '\\' . $className;

		return $this->component->getInstantiableFiltersNamespace() . '\\' . $className;	
    }
	
}