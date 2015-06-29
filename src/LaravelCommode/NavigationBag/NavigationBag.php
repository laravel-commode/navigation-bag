<?php

namespace LaravelCommode\NavigationBag;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Session\Store;
use LaravelCommode\NavigationBag\Interfaces\INavigationBag;

class NavigationBag implements INavigationBag
{
    /**
     * @var \Illuminate\Session\Store
     */
    private $sessionManager;

    /**
     * @var \Illuminate\Routing\UrlGenerator
     */
    private $urlGenerator;

    public function __construct(Store $sessionManager, UrlGenerator $urlGenerator)
    {
        $this->sessionManager = $sessionManager;
        $this->urlGenerator = $urlGenerator;
    }

    protected function getName($name)
    {
        return "navigation-bag.{$name}";
    }

    protected function redirection($url, $status = 302, array $headers = [])
    {
        return new RedirectResponse($url, $status, $headers);
    }

    /**
     * @param $name
     * @return RedirectResponse
     */
    public function redirectTo($name)
    {
        if ($url = $this->getURL($name)) {
            return $this->redirection($url);
        }

        return $this->redirection('/');
    }

    /**
     * @param $name
     * @return mixed|RedirectResponse
     */
    public function redirectAndForget($name)
    {
        $redirect = $this->redirectTo($name);
        $this->forgetURL($name);
        return $redirect;
    }

    public function flashURL($name, $url = null)
    {
        $this->sessionManager->flash($this->getName($name), $this->urlOrCurrent($url));
    }

    public function forgetURL($name)
    {
        $this->sessionManager->forget($this->getName($name));
    }

    public function getURL($name)
    {
        if ($this->sessionManager->has($name = $this->getName($name))) {
            return $this->sessionManager->get($name);
        }

        return false;
    }

    public function putURL($name, $url = null)
    {
        $this->sessionManager->put($this->getName($name), $this->urlOrCurrent($url));
    }

    public function reflash($name)
    {
        $this->sessionManager->keep($this->getName($name));
    }

    private function urlOrCurrent($url = null)
    {
        return $url === null ? $this->urlGenerator->current() : $url;
    }
}
