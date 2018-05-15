# Filterable - elegant filtering of Eloquent models.

## Introduction
The Filterable package was created to provide an easy way to filter Laravel Eloquent models.


## Purpose

- To provide an easy way to dynamically filter an Eloquent model via HTTP GET and POST methods.
- To provide an easy way to manually filter an Eloquent model via a method call.
- To encapsulate filter logic within its own dedicated class.
- To allow filter logic to be re-used between Eloquent models.
- To follow modern OOP coding standards and design patterns.

## How to works
The package uses a factory to determine which filters to apply to a model.  The factory accepts one argument through the constructor, a filter component.   The filter component is used to instruct the Filter Factory on which filters to instantiate.   This approach allows you to have many different instances of a Filter Factory using different components that change its behaviour. 

The request input is used to build the filters for a particular model.  For example, the query string parameter "year_released" will instantiate the "YearReleasedFilter" class and apply the filter to the model.  

## Installation
```
composer require webdevjohn/filterable
```


## Configuration

Complete the following steps.

- Step 1: Create and configure the filter component.
- Step 2: Filter Factory setup.
- Step 3: Create the filters.
- Step 4: Add the Filterable trait to the model that you want to filter.

### Step 1 - Create and configure the filter component
The purpose of the filter component is to instruct the Filter Factory on which filters to instantiate.   The list of filter that can be instantiated for a particular model are listed in the getInstantiableFilters() method.   These can also be organised by modified the namespace that is returned by the getInstantiableFiltersNamespace() method.

To create a filter component from the command line of your Laravel project, type the Artisan command below, specifying the name of the component. 

```
php artisan filter:component {NameOfComponent}
```

You are free to name your filter component classes however you choose.  The only requirement of a filter component is that it must implement the FilterComponentInterface.

The newly created filter component will be placed in the App\Filters\Components directory and namespace by default.

You can override the default directory \ namespace by passing an optional 2nd argument. 
```
php artisan filter:component {NameOfComponent} {namespace}
```

### Step 2 - Filter Factory setup
The filter component created in step 1 needs to be injected into the constructor argument of the FilterFactory.  You can do this by binding a filter factory instance and injecting the filter component.

Open the App/Providers/AppServiceProvider.php file and create a new binding for the FilterFactory in the register() method, specifying an alias for the Factory.  Then pass the component into the FilterFactory constructor using the $app->make()method.

You can use the code below as a template, replacing “FactoryAlias” and “YourComponent”.

```
App/Providers/AppServiceProvider.php    
    
    public function register()
    {
        $this->app->bind('FactoryAlias', function ($app) {
            return new \Webdevjohn\Filterable\FilterFactory(
                $app->make(\App\Filters\Components\YourComponent::class)
            );         
        });   
    }
```

### Step 3- Create Filters
The filters that are created will be put in the App\Filters directory and namespace by default.
```
php artisan filter:make {FilterName}
```

You can override the default directory \ namespace by passing an optional 2nd argument
```
php artisan filter:make {FilterName} {namespace} 
```

Once a filter has been created for a particular model, you will need to update the getInstantiableFilters() method on the filter component so that the factory can create the filter.

### Step 4- The Filterable Trait
Add the Filterable trait to the model that you want to filter:
```
use Webdevjohn\Filterable\Traits\Filterable;
```

The filterable trait exposes the getFilterFactory() method to the model and is used to retrieve the FilterFactory instance from the IOC (inversion of control) container.
```
public function getFilterFactory(string $factory)
{
    return app()->make($factory);
}
```

You also need to add the code snippet below to your model, replacing 'YourFilterFactory' with the name (alias) of the factory that was setup in step 2

The scopeFilters method provides easy access to the FilterFactory that was setup in the IOC container in Step 2. 
```
public function scopeFilters($query, $request)
{
    return $this->getFilterFactory('YourFilterFactory')->make($query, $request);
}
```


## Usage
The example below is calling the Filter() method from a repository, but the concept is that same if used directly in a model or controller.

With any of your existing queries you can call the Filters() method, passing through the $request->input().  This will dynamically instantiate and apply the filters to the model.

```
public function getLatestTracks($request)
{
	return $this->model->WithRelations()	
	                   ->Filters($request->input())
	                   ->orderBy('purchase_date', 'DESC')						
	                   ->take(12)
	                   ->get()
}
```