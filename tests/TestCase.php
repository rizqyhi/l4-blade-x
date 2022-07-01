<?php

namespace Spatie\BladeX\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
// use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\BladeX\BladeXServiceProvider;
use Spatie\BladeX\Facades\BladeX;
use Spatie\BladeX\Tests\SnapshotAssertions\MatchesSnapshots;

abstract class TestCase extends \anlutro\LaravelTesting\PkgAppTestCase
{
    use MatchesSnapshots;

    public function setUp(): void
    {
        parent::setUp();

	if (!is_dir(dirname(__DIR__) . '/build')) {
	    mkdir(dirname(__DIR__) . '/build/views', 0775, true);
	}

        $this->app['path.storage'] = dirname(__DIR__) . '/build';

        $this->app->register(BladeXServiceProvider::class);
    }

    public function tearDown(): void
    {
        array_map('unlink', glob(dirname(__DIR__) . '/build/views/*'));
    }

    public function getVendorPath()
    {
        return dirname(__DIR__) . '/vendor';
    }

    // protected function getExtraProviders()
    // {
    //     return [
    //         BladeXServiceProvider::class
    //     ];
    // }

    protected function getStub(string $fileName): string
    {
        return __DIR__."/previous-stubs/{$fileName}";
    }

    protected function assertMatchesViewSnapshot(string $viewName, array $data = [])
    {
        $fullViewName = "views.{$viewName}";

        $this->assertMatchesXmlSnapshot(
            '<div>'.View::make($fullViewName, $data)->render().'</div>'
        );

        $this->assertMatchesXmlSnapshot(
            '<div>'.Blade::compileString($this->getViewContents($viewName)).'</div>'
        );
    }

    protected function getViewContents(string $viewName): string
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        $testFile = last($backtrace)['file'];

        $baseDirectory = pathinfo($testFile, PATHINFO_DIRNAME);

        $viewFileName = "{$baseDirectory}/stubs/views/{$viewName}.blade.php";

        return file_get_contents($viewFileName);
    }
}
