<?php namespace LaravelCommode\Filters;

    use LaravelCommode\Common\GhostService\GhostService;

    class FiltersServiceProvider extends GhostService
    {
        const InterfaceName = 'LaravelCommode\Filters\Interfaces\IFilterRegistry';
        const ConcreteName = 'LaravelCommode\Filters\Registry\FilterRegistry';
        const ServiceName = 'commode.filters';

        protected $defer = false;

        public function provides()
        {
            return [self::ServiceName, self::InterfaceName];
        }

        protected function launching() { }

        public function boot()
        {
            $this->package('laravel-commode/filters');
        }

        protected function registering()
        {
            $this->app->bind(self::InterfaceName, self::ConcreteName);

            $this->app->bindShared(self::ServiceName, function($app) {
                return $app->make(self::InterfaceName);
            });

            $this->app->before(function ()
            {
                $this->app->make(self::InterfaceName)->register();
            });
        }
    }
