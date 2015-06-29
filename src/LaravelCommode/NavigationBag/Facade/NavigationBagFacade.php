<?php

namespace LaravelCommode\NavigationBag\Facade;

use Illuminate\Support\Facades\Facade;
use LaravelCommode\NavigationBag\NavigationBagServiceProvider;

class NavigationBagFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return NavigationBagServiceProvider::PROVIDES_SERVICE;
    }
}
