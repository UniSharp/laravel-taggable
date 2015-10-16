<?php
namespace Unisharp\Taggable;

use App\Console\Commands\IndependentTagTable;
use Illuminate\Support\ServiceProvider;

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
