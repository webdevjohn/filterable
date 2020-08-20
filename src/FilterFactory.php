<?php

namespace Webdevjohn\Filterable;

use Illuminate\Support\Str;
use Webdevjohn\Filterable\Interfaces\FilterComponentInterface;

class FilterFactory {

	/**
	 * The filter component implementation.
	 *
	 * @var FilterComponentInterface
	 */
	protected $component;

	/**
	 *
	 * @var boolean
	 */
	protected $commonFilterFlag = false;
	
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
		$this->applyIOCFilters($query);
	}


	/**
	 * Dynamically applies multiple filters from the IOC container to the model.
	 *
	 * @param $query
	 * 
	 * @return void
	 */
	protected function applyIOCFilters($query)
	{
		$iocFilters = $this->component->getIOCFilters();

		foreach ($iocFilters as $iocFilter) {
			app()->make($iocFilter)->filter($query, null);
		}	
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
		$this->commonFilterFlag = true;
		
		foreach ($this->appliableFilters($requestInput, $this->component->getInstantiableCommonFilters()) as $requestInputName) 
		{		
			$this->makeFilterFrom($requestInputName)->filter($query, $requestInput[$requestInputName]);
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
	 * 
	 * @return Class  
	 */
	protected function makeFilterFrom(string $className)
	{	
		$class = $this->resolveNamespace($className);

		return new $class;
	}


	/**
	 * Determines which namespace to prepend to the classname.
	 * Returns a fully qualified classname.
	 * 
	 * @param string $className	 
	 * 
	 * @return string 
	 */
	protected function resolveNamespace(string $className)
	{
		$className = Str::studly($className);

		$className .= 'Filter';

		if ($this->commonFilterFlag) return $this->component->getInstantiableCommonFiltersNamespace() . '\\' . $className;

		return $this->component->getInstantiableFiltersNamespace() . '\\' . $className;	
    }
}
