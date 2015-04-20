#Commode: Filters

[![Build Status](https://travis-ci.org/laravel-commode/filters.svg?branch=master)](https://travis-ci.org/laravel-commode/filters)
[![Code Climate](https://codeclimate.com/repos/55350fab695680640100135a/badges/8e626f482702f2520a14/gpa.svg)](https://codeclimate.com/repos/55350fab695680640100135a/feed)
[![Coverage Status](https://coveralls.io/repos/laravel-commode/filters/badge.svg?branch=master)](https://coveralls.io/r/laravel-commode/filters?branch=master)


>**_laravel-commode/filters** is a Filter Layer Helper, which allows you to organize your routing filters 
in classes.

<br />
####Contents

+ <a href="#installing">Installing</a>
+ <a href="#filter_group">Creating a filter group</a>

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

To create a filter group you need to create a `FilterClass`, that must be extended from 
``\LaravelCommode\Filters\Groups\AbstractFilterGroup``. For example, let's say we need an ACL filter group, 
that could be checking whether user is guest or not, check if current authorized user has permissions for actions:
  
    <?php
        namespace Application\Http\Filters;
        
        use LaravelCommode\Filters\Groups\AbstractFilterGroup;
        
        class ACLFilters extends AbstractFilterGroup
        {
            public function getPrefix()
            {
                return 'auth';
            }
        }