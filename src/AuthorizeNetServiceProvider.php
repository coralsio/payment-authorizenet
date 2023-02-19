<?php

namespace Corals\Modules\Payment\AuthorizeNet;

use Illuminate\Support\ServiceProvider;
use Corals\Settings\Facades\Modules;

class AuthorizeNetServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerModulesPackages();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    public function registerModulesPackages()
    {
        Modules::addModulesPackages('corals/payment-authorizenet');
    }
}
