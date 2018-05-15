<?php

namespace Webdevjohn\Filterable\Interfaces;

interface FilterComponentInterface {

	function getInstantiableFiltersNamespace(): string;
	function getInstantiableFilters(): array;
	function getInstantiableCommonFiltersNamespace(): string;
	function getInstantiableCommonFilters(): array;
}