#Commode: Filters

[![Build Status](https://travis-ci.org/laravel-commode/filters.svg?branch=master)](https://travis-ci.org/laravel-commode/filters)
[![Code Climate](https://codeclimate.com/repos/55350fab695680640100135a/badges/8e626f482702f2520a14/gpa.svg)](https://codeclimate.com/repos/55350fab695680640100135a/feed)
[![Coverage Status](https://coveralls.io/repos/laravel-commode/filters/badge.svg?branch=master)](https://coveralls.io/r/laravel-commode/filters?branch=master)


>**_laravel-commode/filters** is a laravel 4.2 Filter Layer helper, which allows you to organize your 
routing filters 
in classes.

<br />
####Contents

+ <a href="#installing">Installing</a>
+ <a href="#filter_group">Creating a filter group</a>
+ <a href="#filter_registry">Filter registry</a>

<hr />

##<a name="installing">Installing</a>

You can install ___laravel-commode/common___ using composer:

    "require": {
        "laravel-commode/filters": "dev-master"
    }

To enable package you need to register ``LaravelCommode\Filters\FiltersServiceProvider`` service provider:

    <?php
        // ./yourLaravelApplication/app/config/app.php
        return [
            // ... config code
            'providers' => [
                // ... providers
                'LaravelCommode\Filters\FiltersServiceProvider'
            ]
        ];


##<a name="filter_group">Creating a filter group</a>

To create a filter group you need to create a ``FilterGroupClass``, that must be extended from 
``LaravelCommode\Filters\Groups\AbstractFilterGroup``. Each ``FilterGroupClass`` needs to implement 
``getPrefix()`` method, that would return prefix, that is common for methods that are going to be registered as 
filters. 
For example, let's say we need an ACL filter group, that could be checking whether user is guest or not, 
and check if current authorized user has permissions for actions. We will create `ACLFilters` class, that extends 
``LaravelCommode\Filters\Groups\AbstractFilterGroup`` and implement ``getPrefix()`` method, that would return 
_'auth'_ value, since methods' names we're gonna need as filters are prefixes with this string:
  
    <?php
        namespace Application\Http\Filters;
        
        use LaravelCommode\Filters\Groups\AbstractFilterGroup;
        use Illuminate\Auth\Guard;
        use Illuminate\Foundation\Application;
        
        class ACLFilters extends AbstractFilterGroup
        {
            /**
            * @var \Illuminate\Auth\Guard 
            **/
            private $authGuard;
            
            /**
            * All constructors are passed into DI-container.
            *
            * But, since \Illuminate\Auth\Guard is registered as shared 
            * view name auth, it can not be injected directly, so in 
            * this example I will use Application injection
            **/
            public function __construct(Application $application)
            {
                $this->authGuard = $application->make('auth'); // grab auth guard from DI 
            }
            
            /**
            * Guest filter
            **/
            public function authGuest()
            {
                if ($this->authGuard->guest()) {
                    return \Redirect::to('security/signin');
                }
            }
            
            /**
            * Dashboard filter
            **/
            public function authDashboard()
            {
                if ($this->authGuest() && !$this->authGuard->user()->hasPermission('dashboard')) {
                    return \Redirect::to('security/signin');
                }
            }
            
            public function getPrefix()
            {
                return 'auth';
            }
        }
        
##<a name="filter_registry">Filter registry</a>

Filter registry is a class that helps you to register your filter groups in laravel environment. It can be 
accessed through ``FilterRegistry`` facade, through laravel's IoC as 'common.filters' or by passing 
``LaravelCommode\Filters\Interfaces\IFilterRegistry`` into it.

To register your filter group you need to pass it's class name into ``FilterRegistry::extract($classname)`` 
method or if you want to register multiple filter groups you need to use 
``FilterRegistry::extractArray(array $classnames = [])``; if you have constructed filter group yourself, 
you can pass it's instance into 
``FilterRegistry::add(\LaravelCommode\Filters\Interfaces\IFilterGroup $filterGroup)``. 

    <?php
        FilterRegistry::extract('Application\Http\Filters\ACLFilters');
        FilterRegistry::extractArray([Application\Http\Filters\ACLFilters::class]); // php 5.5 style
        FilterRegistry::add(new Application\Http\Filters\ACLFilters(app()))