<?php declare(strict_types=1);

namespace Letspaak\LaravelPercy;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;
use Letspaak\LaravelPercy\Contracts\Percy;

class PercyServiceProvider extends ServiceProvider implements DeferrableProvider
{

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/percy.php',
            'percy'
        );

        $this->app->bind(Percy::class, fn() => PercyDusk::class);
    }

    public function boot()
    {
        if($this->app->make('config')->get('percy.dusk')) {
            Browser::macro('snapshot', function () {
                $this->app->make(Percy::class, ['browser' => $this])->snapshot(...func_get_args());
            });
        }

    }

    public function provides()
    {
        return [Percy::class];
    }

}
