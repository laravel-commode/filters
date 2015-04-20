<?php
    namespace LaravelCommode\Filters\Interfaces;

    use Illuminate\Routing\Router;
    use LaravelCommode\Common\Resolver\Resolver;

    /**
     * Created by PhpStorm.
     * User: madman
     * Date: 10/29/14
     * Time: 5:43 PM
     */
    interface IFilterGroup
    {
        public function getPrefix();

        public function isRegistered();

        public function register(Router $router, Resolver $resolver);
    } 