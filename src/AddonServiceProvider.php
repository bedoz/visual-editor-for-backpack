<?php

namespace Bedoz\VisualEditorForBackpack;

use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([realpath(__DIR__.'/config') => config_path()], 'config');

        $this->loadTranslationsFrom(realpath(__DIR__.'/resources/lang'), 'visual-editor-for-backpack');
        $this->publishes([realpath(__DIR__.'/resources/lang') => resource_path('lang/vendor/visual-editor-for-backpack')], 'lang');

        $this->loadViewsFrom(realpath(__DIR__.'/resources/views'), 'visual-editor-for-backpack');
        $this->publishes([realpath(__DIR__.'/resources/views') => resource_path('views/vendor/visual-editor-for-backpack')], 'views');

        $this->loadMigrationsFrom(realpath(__DIR__.'/database/migrations'));
        $this->publishes([realpath(__DIR__.'/database/migrations') => database_path('migrations')], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            realpath(__DIR__.'/config/visual-editor.php'),
            'visual-editor'
        );

        /*$this->loadRoutesFrom(
            realpath(__DIR__.'/routes/visual-editor.php')
        );*/
    }
}