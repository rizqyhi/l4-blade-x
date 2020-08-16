<?php

namespace Spatie\BladeX\ComponentDirectory;

use Illuminate\Support\Facades\View;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;
use Spatie\BladeX\Laravel\Collection;
use Spatie\BladeX\Laravel\Str;

class RegularDirectory extends ComponentDirectory
{
    public function __construct(string $viewDirectory, bool $includeSubdirectories)
    {
        $this->viewDirectory = Str::before($viewDirectory, '.*');
        $this->includeSubdirectories = $includeSubdirectories;
    }

    public function getAbsoluteDirectory(): string
    {
        $viewPath = str_replace('.', '/', $this->viewDirectory);

        $absoluteDirectory = Collection::make(View::getFinder()->getPaths())
            ->map(function (string $path) use ($viewPath) {
                return realpath($path.'/'.$viewPath);
            })
            ->filter()
            ->first();

        if (! $absoluteDirectory) {
            throw CouldNotRegisterComponent::viewPathNotFound($viewPath);
        }

        return $absoluteDirectory;
    }
}
