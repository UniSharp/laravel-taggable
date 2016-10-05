<?php
namespace Unisharp\Taggable\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;

class IndependentCategoryTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taggable:independent_category_table {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Independent Category Table';

    protected $composer;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Composer $composer)
    {
        $this->composer = $composer;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $entity_name = $this->argument('name');
        $exploded_name = explode('\\', $entity_name);
        $entity_short_name = $exploded_name[count($exploded_name) - 1];
        unset($exploded_name[count($exploded_name) - 1]);
        $entity_namespace = implode("\\", $exploded_name);
        $path = $this->createMigration(Str::snake($entity_short_name));
        $content = app('files')->get(__DIR__ . '/stubs/independent_category_table.stub');
        $content = $this->replaceContent(
            [
                'DummyClass'     =>  $entity_short_name,
                'dummy_entity'   =>  Str::snake($entity_short_name),
                'dummy_table'    =>  Str::plural(Str::snake($entity_short_name)),
                'category_table' =>  Str::snake($entity_short_name) . '_categories',
            ],
            $content
        );

        app('files')->put($path, $content);

        $category_entity_name = $entity_short_name . 'Category';
        if (count($exploded_name) > 0) {
            $category_entity_name = $entity_namespace . '\\' . $entity_short_name . 'Category';
        }

        if (!app('files')->exists(app_path(str_replace('\\', '/', $entity_namespace)))) {
            app('files')->makeDirectory(app_path(str_replace('\\', '/', $entity_namespace)), 0755, true);
        }

        app('files')->put(
            app_path(
                str_replace('\\', '/', $entity_namespace) . '/' . $entity_short_name . 'Category.php'
            ),
            $this->replaceContent(
                ['DummyClass' => $entity_short_name],
                app('files')->get(__DIR__ . '/stubs/IndependentCategory.stub')
            )
        );

        $this->composer->dumpAutoloads('-o');
    }

    public function replaceContent(Array $strings, $content)
    {
        foreach ($strings as $key => $string) {
            $content = str_replace($key, $string, $content);
        }

        return $content;
    }

    public function createMigration($table_name)
    {
        $path = app()->databasePath() . '/migrations';
        $name = 'create_' . $table_name . '_categories_table';
        return app('migration.creator')->create($name, $path);
    }
}
