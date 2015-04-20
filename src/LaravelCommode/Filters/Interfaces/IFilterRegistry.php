<?php
    namespace LaravelCommode\Filters\Interfaces;

    interface IFilterRegistry
    {
        public function add(IFilterGroup $filterGroup, $forceRegister = true);
        public function extract($filterGroup, $forceRegister = true);
        public function extractArray(array $filterGroup, $forceRegister = true);
        public function get($prefix);

        public function register();
    } 