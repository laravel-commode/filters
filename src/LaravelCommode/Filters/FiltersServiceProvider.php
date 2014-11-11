<?php namespace LaravelCommode\Filters;

    use LaravelCommode\Common\GhostService\GhostService;
    use LaravelCommode\Filters\Registry\FilterRegistry;
    use LaravelCommode\Filters\Interfaces\IFilterRegistry;

    class FiltersServiceProvider extends GhostService
    {
        protected $defer = false;

        public function provides()
        {
            return ['commode.filters', IFilterRegistry::class];
        }

        public function launching() { }

        public function boot()
        {
            $this->package('laravel-commode/filters');
        }

        public function registering()
        {
            $this->app->bind(IFilterRegistry::class, FilterRegistry::class);

            $this->app->bindShared('commode.filters', function(){
                return $this->app->make(IFilterRegistry::class);
            });

            $this->app->before(function ()
            {
                $this->app->make('commode.filters')->register();
            });
        }
    }
