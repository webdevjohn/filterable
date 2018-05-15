<?php

namespace Webdevjohn\Filterable\Interfaces;

interface FilterableInterface {
	function filter($query, $arg);
}