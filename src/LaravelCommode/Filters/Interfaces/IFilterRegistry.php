<?php
    namespace LaravelCommode\Filters\Interfaces;

    use LaravelCommode\Filters\Interfaces\IFilterGroup;

    interface IFilterRegistry
    {
        public function add(IFilterGroup $filterGroup);
        public function extract($filterGroup);
        public function extractArray(array $filterGroup);
        public function get($prefix);

        public function register();
    } 