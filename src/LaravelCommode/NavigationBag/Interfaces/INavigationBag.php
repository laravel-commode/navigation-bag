<?php

namespace LaravelCommode\NavigationBag\Interfaces;

use Illuminate\Http\RedirectResponse;

interface INavigationBag
{
    /**
     * @param $name
     * @return RedirectResponse
     */
    public function redirectTo($name);

    /**
     * @param $name
     * @return mixed|RedirectResponse
     */
    public function redirectAndForget($name);

    public function flashURL($name, $url = null);
    public function forgetURL($name);

    public function getURL($name);
    public function putURL($name, $url = null);

    public function reflash($name);
}
