<?php
namespace Unisharp\Taggable;

use Illuminate\Support\ServiceProvider;
use Unisharp\Taggable\Console\Commands\IndependentCategoryTable;
use Unisharp\Taggable\Console\Commands\IndependentTagTable;

class TaggableServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->singleton('taggable.independent.tag.table', function ($app) {
            return new IndependentTagTable($app['composer']);
        });

        $this->app->singleton('taggable.independent.category.table', function ($app) {
            return new IndependentCategoryTable($app['composer']);
        });

        $this->commands('taggable.independent.tag.table');
        $this->commands('taggable.independent.category.table');
    }
}
