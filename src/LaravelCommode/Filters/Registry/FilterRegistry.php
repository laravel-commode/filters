<?php
    namespace LaravelCommode\Filters\Registry;

    use LaravelCommode\Common\Registry\ResolverAccess;
    use LaravelCommode\Common\Resolver\Resolver;
    use LaravelCommode\Filters\Groups\AbstractFilterGroup;
    use LaravelCommode\Filters\Interfaces\IFilterGroup;
    use LaravelCommode\Filters\Interfaces\IFilterRegistry;

    use Illuminate\Routing\Router;

    /**
     * Class FilterRegistry
     * @package LaravelCommode\Filters\Registry
     */
    class FilterRegistry extends ResolverAccess implements IFilterRegistry
    {
        /**
         * @var IFilterGroup[]
         */
        protected $filters = [];

        /**
         * @var Router
         */
        private $router;

        /**
         * @var Resolver
         */
        private $resolver;

        public function lazyLoading()
        {
            return true;
        }

        public function __construct(Router $router, Resolver $resolver)
        {
            $this->router = $router;
            $this->resolver = $resolver;
        }

        protected function getContainerName()
        {
            return 'filters';
        }

        /**
         * @param $offset
         * @return AbstractFilterGroup
         */
/*        public function __get($offset)
        {
            return parent::__get($offset);
        }*/

        protected function onSet(&$offset, $value)
        {
            /**
             * @var AbstractFilterGroup $value
             */
            $value = parent::onSet($offset, $value);

            if ($this->lazyLoading()) {
                if (!$value->isRegistered()) {
                    $value->register($this->router, $this->resolver);
                }
            }

            $offset = $value->getPrefix();

            return $value;
        }

        public function register()
        {
            /**
             * @var AbstractFilterGroup $value
             */
            foreach($this as $key => $value) {
                if (!$value->isRegistered()) {
                    $value->register($this->router, $this->resolver);
                }
            }
        }

        public function add(IFilterGroup $filterGroup, $forceRegister = true)
        {
            $this[] = $filterGroup;
        }

        public function extract($filterGroup, $forceRegister = true)
        {
            $this[] = $filterGroup;
        }

        public function extractArray(array $filterGroups, $forceRegister = true)
        {
            foreach($filterGroups as $filterGroup) {
                $this->extract($filterGroup, $forceRegister);
            }
        }

        /**
         * @param string $prefix
         * @return \LaravelCommode\Filters\Interfaces\IFilterGroup
         */
        public function get($prefix)
        {
            return $this[$prefix];
        }
    }