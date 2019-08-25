<?php

namespace Yangxue93\Idiom\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'idiom:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the idiom package';

    /**
     * Install directory.
     *
     * @var string
     */
    protected $directory = '';
    protected $save_dir = '';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->initDatabase();

        $this->initIdiomDirectory();
    }

    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function initDatabase()
    {
        $this->call('migrate');

        $userModel = config('idiom.database.rd_cyjl');

        /*if ($userModel::count() == 0) {
            $this->call('db:seed', ['--class' => \Encore\Admin\Auth\Database\AdminTablesSeeder::class]);
        }*/
    }

    /**
     * Initialize the admAin directory.
     *
     * @return void
     */
    protected function initIdiomDirectory()
    {
        $this->directory = config('idiom.directory');
        $this->save_dir = 'Controllers'.DIRECTORY_SEPARATOR.'API'.DIRECTORY_SEPARATOR.'Idiom';
//        $this->line("<info>{$this->routes_dir} </info> ");
//        return;
        if (is_dir($this->directory.$this->save_dir)) {
            $this->line('<info>'.$this->directory.DIRECTORY_SEPARATOR.$this->save_dir.' directory already exists !</info> ');
            //            return;
        }else{
            $this->makeDir($this->save_dir);
            $this->line('<info>Idiom directory was created:</info> '.str_replace(base_path(), '', $this->save_dir));
        }

        $this->createLoopController();
        $this->createRoutesFile();
    }

    /**
     * Create LoopController.
     *
     * @return void
     */
    public function createLoopController()
    {
        $IdiomController = $this->directory.DIRECTORY_SEPARATOR.$this->save_dir.DIRECTORY_SEPARATOR.'IdiomController.php';
        $contents = $this->getStub('IdiomController');
        $this->laravel['files']->put(
            $IdiomController,
            str_replace('myNamespace', config('idiom.route.namespace').'\\API\\Idiom', $contents)
        );
        $this->line('<info>IdiomController file was created:</info> '.str_replace(base_path(), '', $IdiomController));
    }
    /**
     * Create routes file.
     *
     * @return void
     */
    protected function createRoutesFile()
    {

        $file = base_path('routes'.DIRECTORY_SEPARATOR.'idiom.php');

        $contents = $this->getStub('routes');
        $this->laravel['files']->put($file, str_replace('myNamespace', config('loop.route.namespace'), $contents));
        $this->line('<info>Routes file was created:</info> '.str_replace(base_path(), '', $file));
    }

    /**
     * Get stub contents.
     *
     * @param $name
     *
     * @return string
     */
    protected function getStub($name)
    {
        return $this->laravel['files']->get(__DIR__."/stubs/$name.stub");
    }

    /**
     * Make new directory.
     *
     * @param string $path
     */
    protected function makeDir($path = '')
    {
        $this->laravel['files']->makeDirectory($this->directory.DIRECTORY_SEPARATOR.$path, 0755, true, true);
    }
}
