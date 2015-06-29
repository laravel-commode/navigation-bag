<?php namespace LaravelCommode\NavigationBag;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\RoutingServiceProvider;
use Illuminate\Session\SessionServiceProvider;
use Illuminate\Support\ServiceProvider;
use LaravelCommode\NavigationBag\Facade\NavigationBagFacade;
use LaravelCommode\NavigationBag\Interfaces\INavigationBag;
use LaravelCommode\SilentService\SilentService;

class NavigationBagServiceProvider extends SilentService
{
    const PROVIDES_SERVICE = 'laravel-commode.navigation-bag';

    public function provides()
    {
        return [self::PROVIDES_SERVICE, INavigationBag::class];
    }

    public function aliases()
    {
        return [
            'NavBag' => NavigationBagFacade::class
        ];
    }

    protected function uses()
    {
        return [SessionServiceProvider::class, RoutingServiceProvider::class];
    }

    /**
     * This method will be triggered instead
     * of original ServiceProvider::register().
     * @return mixed
     */
    public function registering()
    {
        $this->app->bind(self::PROVIDES_SERVICE, NavigationBag::class);

        $this->app->bind(INavigationBag::class, NavigationBag::class);
    }

    /**
     * This method will be triggered instead
     * when application's booting event is fired.
     * @return mixed
     */
    public function launching()
    {
    }
}
