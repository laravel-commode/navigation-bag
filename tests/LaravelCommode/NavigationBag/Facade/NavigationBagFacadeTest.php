<?php

namespace LaravelCommode\NavigationBag\Facade;

use LaravelCommode\NavigationBag\NavigationBagServiceProvider;

class NavigationBagFacadeTest extends \PHPUnit_Framework_TestCase
{
    public function testFacade()
    {
        $accessorMethod = new \ReflectionMethod(NavigationBagFacade::class, 'getFacadeAccessor');
        $accessorMethod->setAccessible(true);
        $this->assertSame(NavigationBagServiceProvider::PROVIDES_SERVICE, $accessorMethod->invoke(null));
        $accessorMethod->setAccessible(false);
    }
}
