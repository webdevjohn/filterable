<?php

namespace Webdevjohn\Filterable\Traits;

trait Filterable {

	/**
	 * Returns a FilterFactory.
	 *
	 * @return FilterFactory
	 */
	public function getFilterFactory(string $factory)
	{
		return app()->make($factory);
	}

}