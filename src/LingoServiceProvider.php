<?php

namespace ctf0\Lingo;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class LingoServiceProvider extends ServiceProvider
{
    protected $file;

    /**
     * Perform post-registration booting of services.
     */
    public function boot(Filesystem $file)
    {
        $this->file = $file;

        $this->packagePublish();

        // append extra data
        if (!app('cache')->store('file')->has('ct-lingo')) {
            $this->autoReg();
        }
    }

    /**
     * [packagePublish description].
     *
     * @return [type] [description]
     */
    public function packagePublish()
    {
        // resources
        $this->publishes([
            __DIR__ . '/resources/assets' => resource_path('assets/vendor/Lingo'),
        ], 'assets');

        // views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Lingo');
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/Lingo'),
        ], 'views');

        // trans
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Lingo');
        $this->publishes([
            __DIR__ . '/resources/lang' => resource_path('lang/vendor/Lingo'),
        ], 'trans');
    }

    /**
     * [autoReg description].
     *
     * @return [type] [description]
     */
    protected function autoReg()
    {
        // routes
        $route_file = base_path('routes/web.php');
        $search     = 'Lingo';

        if ($this->checkExist($route_file, $search)) {
            $data = "\n// Lingo\nctf0\Lingo\LingoRoutes::routes();";

            $this->file->append($route_file, $data);
        }

        // mix
        $mix_file = base_path('webpack.mix.js');
        $search   = 'Lingo';

        if ($this->checkExist($mix_file, $search)) {
            $data = "\n// Lingo\nrequire('dotenv').config()\nmix.sass('resources/assets/vendor/Lingo/sass/' + process.env.MIX_LINGO_FRAMEWORK + '.scss', 'public/assets/vendor/Lingo/style.css').version();";

            $this->file->append($mix_file, $data);
        }

        // fw
        $env_file = base_path('.env');
        $search   = 'MIX_LINGO_FRAMEWORK';

        if ($this->checkExist($env_file, $search)) {
            $data = "\nMIX_LINGO_FRAMEWORK=bulma";

            $this->file->append($env_file, $data);
        }

        // run check once
        app('cache')->store('file')->rememberForever('ct-lingo', function () {
            return 'added';
        });
    }

    /**
     * [checkExist description].
     *
     * @param [type] $file   [description]
     * @param [type] $search [description]
     *
     * @return [type] [description]
     */
    protected function checkExist($file, $search)
    {
        return $this->file->exists($file) && !str_contains($this->file->get($file), $search);
    }

    /**
     * Register any package services.
     */
    public function register()
    {
        $this->app->register(\Themsaid\Langman\LangmanServiceProvider::class);
        $this->app->register(\ctf0\PackageChangeLog\PackageChangeLogServiceProvider::class);
    }
}
