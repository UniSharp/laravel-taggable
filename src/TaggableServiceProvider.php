<?php
namespace Unisharp\Taggable;

use Illuminate\Support\ServiceProvider;
use Unisharp\Taggable\Console\Commands\IndependentTagTable;

class TaggableServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->singleton('taggable.independent.table', function ($app) {
            return new IndependentTagTable($app['composer']);
        });

        $this->commands('taggable.independent.table');
    }
}
