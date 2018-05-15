<?php

namespace Webdevjohn\Filterable;

use Illuminate\Support\ServiceProvider;
use Webdevjohn\Filterable\Commands\MakeFilter;
use Webdevjohn\Filterable\Commands\MakeFilterComponent;

class FilterableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){}

        
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(MakeFilter::class);
        $this->commands(MakeFilterComponent::class);
    }
}