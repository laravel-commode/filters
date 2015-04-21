<?php
    namespace LaravelCommode\Filters\Facade;

    use Illuminate\Support\Facades\Facade;
    use LaravelCommode\Filters\FiltersServiceProvider;

    class FilterRegistry extends Facade
    {
        protected static function getFacadeAccessor()
        {
            return [FiltersServiceProvider::ServiceName];
        }
    }