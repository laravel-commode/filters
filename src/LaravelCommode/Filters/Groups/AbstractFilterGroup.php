<?php namespace LaravelCommode\Filters\Groups;

    use LaravelCommode\Common\Reflections\ReflectionMethodsFilter;
    use LaravelCommode\Common\Resolver\Resolver;
    use LaravelCommode\Filters\Interfaces\IFilterGroup;

    use Illuminate\Routing\Router;

    abstract class AbstractFilterGroup implements IFilterGroup
    {
        private $registered = false;

        abstract public function getPrefix();

        public function isRegistered()
        {
            return $this->registered;
        }

        public function register(Router $router, Resolver $resolver)
        {
            $reflection = new ReflectionMethodsFilter($this);

            foreach($methods = $reflection->filterPrefix($this->getPrefix()) as $method)
            {
                $router->filter($method, $resolver->methodToClosure($this, $method));
            }

            $this->registered = true;
        }
    }