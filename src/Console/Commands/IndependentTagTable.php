<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Composer;
use Illuminate\Support\Str;

class IndependentTagTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taggable:independent_table {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Independent Tag Table';

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
        $content = app('files')->get(__DIR__ . '/stubs/independent_tag_table.stub');
        $content = $this->replaceContent(
            [
                'DummyClass' => $entity_short_name,
                'dummy_table' =>  Str::snake($entity_short_name)
            ],
            $content
        );
        app('files')->put($path, $content);

        $tag_entity_name = $entity_short_name . 'Tag';
        if (count($exploded_name) > 0) {
            $tag_entity_name = $entity_namespace . '\\' . $entity_short_name . 'Tag';
        }

        if (!app('files')->exists(app_path(str_replace('\\', '/', $entity_namespace)))) {
            app('files')->makeDirectory(app_path(str_replace('\\', '/', $entity_namespace)), 0755, true);
        }

        app('files')->put(
            app_path(
                str_replace('\\', '/', $entity_namespace) . '/' . $entity_short_name . 'Tag.php'
            ),
            $this->replaceContent(
                ['DummyClass' => $entity_short_name],
                app('files')->get(__DIR__ . '/stubs/independentTag.stub')
            )
        );

        $this->composer->dumpAutoloads();
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
        $name = 'create_' . $table_name . '_tags_table';
        return app('migration.creator')->create($name, $path);
    }
}
