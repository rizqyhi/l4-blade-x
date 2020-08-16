<?php

namespace Spatie\BladeX;

use Illuminate\Support\ServiceProvider;

class BladeXServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(BladeX::class);
        $this->app->singleton(ContextStack::class);

        $this->app->alias(BladeX::class, 'blade-x');
    }

    public function boot()
    {
        $this->registerViewFactory();
        $this->registerBladeCompiler();
        $this->registerBladeXCompiler();
    }

    private function registerViewFactory()
    {
        $this->app->bindShared('view', function($app) {
			$factory = new CustomFactory(
                $app['view.engine.resolver'],
                $app['view.finder'],
                $app['events']
            );

			$factory->setContainer($app);
			$factory->share('app', $app);

			return $factory;
        });
    }

    private function registerBladeCompiler()
    {
        $this->app->bindShared('blade.compiler', function($app) {
			$cache = $app['path.storage'].'/views';

			return new CustomBladeCompiler($app['files'], $cache);
        });
    }

    private function registerBladeXCompiler()
    {
        $this->app['blade.compiler']->extend(function ($view, $compiler) {
            return $this->app[Compiler::class]->compile($view);
        });

        $this->app['view']->addNamespace('bladex', __DIR__.'/../resources/views');

        $this->app->make(BladeX::class)->component('bladex::context', 'context');
    }
}
