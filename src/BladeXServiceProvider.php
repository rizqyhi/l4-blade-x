<?php

namespace Spatie\BladeX;

use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\ViewServiceProvider;

class BladeXServiceProvider extends ViewServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->singleton(BladeX::class);
        $this->app->singleton(ContextStack::class);

        $this->app->alias(BladeX::class, 'blade-x');

        $this->registerBladeXCompiler();
    }

    public function registerFactory()
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

    public function registerBladeEngine($resolver)
    {
		$this->app->bindShared('blade.compiler', function($app)
		{
			$cache = $app['path.storage'].'/views';

			return new CustomBladeCompiler($app['files'], $cache);
		});

		$resolver->register('blade', function()
		{
			return new CompilerEngine($this->app['blade.compiler'], $this->app['files']);
		});
    }

    private function registerBladeXCompiler()
    {
        $this->app['view']->addNamespace('bladex', __DIR__.'/../resources/views');

        $this->app['blade.compiler']->extend(function ($view) {
            return $this->app->make(Compiler::class)->compile($view);
        });

        $this->app->make(BladeX::class)->component('bladex::context', 'context');
    }
}
