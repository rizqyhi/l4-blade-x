<?php

namespace Spatie\BladeX\ComponentDirectory;

use Illuminate\Support\Facades\File;
use Spatie\BladeX\Laravel\Str;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

abstract class ComponentDirectory
{
    /** @var string */
    protected $viewDirectory;

    /** @var bool */
    protected $includeSubdirectories;

    abstract public function getAbsoluteDirectory(): string;

    public function getViewName(SplFileInfo $viewFile): string
    {
        $subDirectory = $viewFile->getRelativePath();

        $view = Str::replaceLast('.blade.php', '', $viewFile->getFilename());

        return implode('.', array_filter([
            $this->viewDirectory,
            $subDirectory !== './' ? rtrim($subDirectory, '/') : '',
            $view,
        ]));
    }

    /**
     * @todo need to find better implementation for this method
     * also related to line 28
     * File::files/File::allFiles supposed to return array of Symfony's SplFileInfo
     */
    public function getFiles(): array
    {
        $filesystem = new Filesystem();

        return array_map(function ($filePath) use ($filesystem) {
            $filename = basename($filePath);
            $relativePath = $filesystem->makePathRelative(dirname($filePath), $this->getAbsoluteDirectory());

            return new SplFileInfo(
                $filename,
                $relativePath,
                $relativePath.$filename
            );
        }, $this->includeSubdirectories
            ? File::allFiles($this->getAbsoluteDirectory())
            : File::files($this->getAbsoluteDirectory())
        );
    }
}
