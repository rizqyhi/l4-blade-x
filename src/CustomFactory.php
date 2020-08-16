<?php

namespace Spatie\BladeX;

use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\ViewFinderInterface;
use Illuminate\View\Factory;

class CustomFactory extends Factory
{
    use ManagesComponents;

    public function __construct(EngineResolver $engines, ViewFinderInterface $finder, Dispatcher $events)
	{
		parent::__construct($engines, $finder, $events);

		$this->share('__env', $this);
	}
}
