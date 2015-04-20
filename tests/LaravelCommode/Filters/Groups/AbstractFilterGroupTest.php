<?php
    namespace LaravelCommode\Filters\Groups;


    use Illuminate\Routing\Router;
    use LaravelCommode\Common\Resolver\Resolver;
    use PHPUnit_Framework_MockObject_MockObject as MockObject;

    class TestSubject extends AbstractFilterGroup
    {

        public function authGuest()
        {
            return true;
        }

        public function getPrefix()
        {
            return 'auth';
        }
    }

    class AbstractFilterGroupTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @var AbstractFilterGroup|MockObject
         */
        private $filterGroup;

        /**
         * @var Router|MockObject
         */
        private $routingMock;

        /**
         * @var Resolver|MockObject
         */
        private $resolverMock;

        private $setMethods = [
            'authGuest',
            'getPrefix'
        ];

        public function setUp()
        {
            parent::setUp();
            $this->filterGroup = new TestSubject();

            $this->routingMock = $this->getMock('Illuminate\Routing\Router', [], [], "", false);
            $this->resolverMock = $this->getMock('LaravelCommode\Common\Resolver\Resolver', [], [], "", false);
        }

        public function testRegistration()
        {
            $this->assertFalse($this->filterGroup->isRegistered());

            $this->routingMock->expects($this->once())->method('filter');
            $this->resolverMock->expects($this->once())->method('methodToClosure');

            $this->filterGroup->register($this->routingMock, $this->resolverMock);

            $this->assertTrue($this->filterGroup->isRegistered());
        }
    }
