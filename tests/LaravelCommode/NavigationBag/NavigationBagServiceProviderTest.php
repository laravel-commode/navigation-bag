<?php

namespace LaravelCommode\NavigationBag;

use Illuminate\Routing\RoutingServiceProvider;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\SessionInterface;
use Illuminate\Session\SessionServiceProvider;
use Illuminate\Session\Store;

use LaravelCommode\NavigationBag\Facade\NavigationBagFacade;
use LaravelCommode\NavigationBag\Interfaces\INavigationBag;
use LaravelCommode\Utils\Tests\PHPUnitContainer;

use PHPUnit_Framework_MockObject_MockObject as Mock;

class NavigationBagServiceProviderTest extends PHPUnitContainer
{
    /**
     * @var NavigationBagServiceProvider
     */
    private $testInstance;

    /**
     * @var SessionInterface|Store|Mock
     */
    private $sessionStorage;

    /**
     * @var UrlGenerator|Mock
     */
    private $urlGenerator;

    protected function setUp()
    {
        parent::setUp();

        $this->sessionStorage = $this->getMock(Store::class, [], [], '', false);
        $this->urlGenerator = $this->getMock(UrlGenerator::class, [], [], '', false);

        $this->testInstance = new NavigationBagServiceProvider($this->getApplicationMock());
    }

    public function testRegistering()
    {
        $this->getApplicationMock()->expects($this->at(0))->method('bind')
            ->with(NavigationBagServiceProvider::PROVIDES_SERVICE, NavigationBag::class);

        $this->getApplicationMock()->expects($this->at(1))->method('bind')
            ->with(INavigationBag::class, NavigationBag::class);

        $this->testInstance->registering();
    }

    public function testLaunching()
    {
        $this->testInstance->launching();
    }

    public function testAliases()
    {
        $this->assertSame(['NavBag' => NavigationBagFacade::class], $this->testInstance->aliases());
    }

    public function testUses()
    {
        $usesMethod = new \ReflectionMethod($this->testInstance, 'uses');
        $usesMethod->setAccessible(true);
        $this->assertSame(
            [SessionServiceProvider::class, RoutingServiceProvider::class],
            $usesMethod->invoke($this->testInstance)
        );
        $usesMethod->setAccessible(false);
    }

    public function testProvides()
    {
        $this->assertSame(
            [NavigationBagServiceProvider::PROVIDES_SERVICE, INavigationBag::class],
            $this->testInstance->provides()
        );
    }

    protected function tearDown()
    {
        unset($this->testInstance);
        parent::tearDown();
    }
}
