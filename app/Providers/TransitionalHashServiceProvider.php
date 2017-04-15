<?php
namespace App\Providers;

use App\Extensions\TransitionalHasher;

class TransitionalHashServiceProvider extends \Illuminate\Hashing\HashServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'hash',
            function () {
                return new TransitionalHasher;
            }
        );
    }
}
