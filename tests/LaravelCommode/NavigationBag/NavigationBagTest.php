<?php

namespace LaravelCommode\NavigationBag;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\Store;

use LaravelCommode\NavigationBag\Interfaces\INavigationBag;
use LaravelCommode\Utils\Tests\PHPUnitContainer;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

use PHPUnit_Framework_MockObject_MockObject as Mock;

class NavigationBagTest extends PHPUnitContainer
{
    /**
     * @var INavigationBag|NavigationBag
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

    public function testReflash()
    {
        $name = uniqid();

        $this->sessionStorage->expects($this->once())->method('keep')
            ->with('navigation-bag.'.$name);

        $this->testInstance->reflash($name);
    }

    public function testPutURL()
    {
        $this->sessionStorage->expects($this->exactly(2))->method('put');

        $this->urlGenerator->expects($this->once())->method('current')
            ->will($this->returnValue('http://'.uniqid().'.com'));

        $this->testInstance->putURL(uniqid());
        $this->testInstance->putURL(uniqid(), 'http://link.com');
    }

    public function testGetURL()
    {
        $name1 = uniqid('name');
        $name2 = uniqid('name');

        $this->sessionStorage->expects($this->at(0))->method('has')
            ->with('navigation-bag.'.$name1)
            ->will($this->returnValue(false));

        $this->sessionStorage->expects($this->at(1))->method('has')
            ->with('navigation-bag.'.$name2)
            ->will($this->returnValue(true));

        $this->sessionStorage->expects($this->once())->method('get')
            ->will($this->returnValue($get = uniqid('http://')));

        $this->assertFalse($this->testInstance->getURL($name1));
        $this->assertSame($get, $this->testInstance->getURL($name2));
    }

    public function testForgetURL()
    {
        $this->sessionStorage->expects($this->once())->method('forget')
            ->with('navigation-bag.'.($name = uniqid()));

        $this->testInstance->forgetURL($name);
    }

    public function testFlashURL()
    {
        $this->sessionStorage->expects($this->at(0))->method('flash')
            ->with('navigation-bag.'.($name1 = uniqid()));

        $this->sessionStorage->expects($this->at(1))->method('flash')
            ->with('navigation-bag.'.($name2 = uniqid()));

        $this->testInstance->flashURL($name1);
        $this->testInstance->flashURL($name2, 'http://link.com');
    }

    public function testRedirect()
    {
        $this->sessionStorage->expects($this->at(0))->method('has')
            ->with('navigation-bag.link1')
            ->will($this->returnValue(false));

        $this->sessionStorage->expects($this->at(1))->method('has')
            ->with('navigation-bag.link2')
            ->will($this->returnValue(true));

        $this->sessionStorage->expects($this->once())->method('get')
            ->with('navigation-bag.link2')
            ->will($this->returnValue("http://link.com"));


        $this->assertTrue($this->testInstance->redirectTo('link1') instanceof RedirectResponse);
        $this->assertTrue($this->testInstance->redirectTo('link2', "http://link.com") instanceof RedirectResponse);
    }

    public function testRedirectAndForget()
    {
        $this->sessionStorage->expects($this->once())->method('forget');

        $this->testInstance->redirectAndForget(uniqid());
    }

    protected function setUp()
    {
        parent::setUp();

        $this->sessionStorage = $this->getMock(Store::class, [], [], '', false);
        $this->urlGenerator = $this->getMock(UrlGenerator::class, [], [], '', false);

        $this->testInstance = new NavigationBag($this->sessionStorage, $this->urlGenerator);
    }

    public function testConstruct()
    {

    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
