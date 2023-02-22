<?php

namespace Corals\Modules\Payment\AuthorizeNet;

use Corals\Foundation\Providers\BasePackageServiceProvider;
use Corals\Settings\Facades\Modules;

class AuthorizeNetServiceProvider extends BasePackageServiceProvider
{
    /**
     * @var
     */
    protected $defer = false;
    /**
     * @var
     */
    protected $packageCode = 'corals-payment-authorizenet';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function bootPackage()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerPackage()
    {
    }

    public function registerModulesPackages()
    {
        Modules::addModulesPackages('corals/payment-authorizenet');
    }
}
