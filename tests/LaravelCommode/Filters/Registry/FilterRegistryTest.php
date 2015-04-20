<?php
    namespace LaravelCommode\Filters\Registry;

    use Illuminate\Routing\Router;
    use Illuminate\Support\Facades\Facade;
    use LaravelCommode\Common\Resolver\Resolver;
    use LaravelCommode\Filters\Groups\AbstractFilterGroup;
    use LaravelCommode\Filters\Groups\TestSubject;
    use LaravelCommode\Filters\Interfaces\IFilterGroup;
    use PHPUnit_Framework_MockObject_MockObject as MockObject;

    class FilterRegistryTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @var FilterRegistry
         */
        private $filterRegistry;

        /**
         * @var Router|MockObject
         */
        private $routingMock;

        /**
         * @var Resolver|MockObject
         */
        private $resolverMock;

        /**
         * @var AbstractFilterGroup|IFilterGroup|TestSubject
         */
        private $testSubject;

        /**
         * @var \Illuminate\Foundation\Application|MockObject $app
         */
        private $applicationMock;

        public function setUp()
        {
            parent::setUp();
            $this->resolverMock = $this->getMock('LaravelCommode\Common\Resolver\Resolver', [], [], '', false);
            $this->routingMock = $this->getMock('Illuminate\Routing\Router', [], [], '', false);
            $this->filterRegistry = new FilterRegistry($this->routingMock, $this->resolverMock);
            $this->applicationMock  = $this->getMock('Illuminate\Foundation\Application', ['make']);
            $this->testSubject = new TestSubject();

            Facade::setFacadeApplication($this->applicationMock);
        }

        public function testLazyLoading()
        {
            $this->assertTrue($this->filterRegistry->lazyLoading());
        }

        public function testAdd()
        {
            $this->filterRegistry->add($this->testSubject);
            $this->assertSame($this->testSubject, $this->filterRegistry->get('auth'));
        }

        public function testRegister()
        {
            $this->filterRegistry->register();
        }

        public function testExtract()
        {
            $this->applicationMock->expects($this->at(0))->method('make')
                ->with(get_class($this->testSubject))
                ->will($this->returnValue($this->testSubject));

            $this->filterRegistry->extractArray([get_class($this->testSubject)]);
        }

        public function tearDown()
        {
            Facade::setFacadeApplication(null);
        }
    }
