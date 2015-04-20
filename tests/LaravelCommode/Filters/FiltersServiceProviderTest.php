<?php namespace LaravelCommode\Filters;

    use Illuminate\Foundation\Application;
    use Illuminate\Routing\Router;
    use LaravelCommode\Common\Resolver\Resolver;
    use LaravelCommode\Filters\Interfaces\IFilterRegistry;
    use LaravelCommode\Filters\Registry\FilterRegistry;
    use PHPUnit_Framework_MockObject_MockObject as MockObject;

    class FiltersServiceProviderTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @var FiltersServiceProvider
         */
        private $testedService;

        /**
         * @var Application|MockObject
         */
        private $applicationMock;

        /**
         * @var Router|MockObject
         */
        private $routingMock;

        /**
         * @var Resolver|MockObject
         */
        private $resolverMock;

        public function setUp()
        {
            parent::setUp();

            $this->applicationMock = $this->getMock('Illuminate\Foundation\Application', [
                'bind', 'bindShared', 'before', 'make'
            ]);

            $this->routingMock = $this->getMock('Illuminate\Routing\Router', ['filter'], [], '', false);
            $this->resolverMock = $this->getMock('LaravelCommode\Common\Resolver\Resolver', [], [], '', false);

            $this->testedService = new FiltersServiceProvider($this->applicationMock);
        }

        public function testRegister()
        {
            $filterRegistry = new FilterRegistry($this->routingMock, $this->resolverMock);

            $this->applicationMock->expects($this->once())->method('bind')
                ->with(FiltersServiceProvider::InterfaceName, FiltersServiceProvider::ConcreteName);

            $this->applicationMock->expects($this->any())->method('make')
                ->with(FiltersServiceProvider::InterfaceName)
                ->will($this->returnCallback(function () use($filterRegistry){
                    return $filterRegistry;
                }));


            $this->applicationMock->expects($this->once())->method('bindShared')
                ->with(FiltersServiceProvider::ServiceName, $this->callback(function($callback) {
                    $fR = ($callback($this->applicationMock));
                    $isok = ($fR instanceof FilterRegistry);
                    return true;
                }));


            $this->applicationMock->expects($this->once())->method('before')
                ->will($this->returnCallback(function($callback) {
                    $callback();
                    return null;
                }));

            $reflectionMethod = new \ReflectionMethod($this->testedService, 'registering');
            $reflectionMethod->setAccessible(true);
            $reflectionMethod->invokeArgs($this->testedService, []);

            $reflectionMethod = new \ReflectionMethod($this->testedService, 'launching');
            $reflectionMethod->setAccessible(true);
            $reflectionMethod->invokeArgs($this->testedService, []);
        }

        public function testPackage()
        {
            $this->assertSame(
                [FiltersServiceProvider::ServiceName, FiltersServiceProvider::InterfaceName],
                $this->testedService->provides()
            );
        }

        public function tearDown()
        {
            unset($this->testedService);
            unset($this->applicationMock);
            unset($this->resolverMock);
            unset($this->routingMock);
        }
    }
