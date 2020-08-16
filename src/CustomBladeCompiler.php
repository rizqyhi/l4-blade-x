<?php

namespace Spatie\BladeX;

use Illuminate\View\Compilers\BladeCompiler;

class CustomBladeCompiler extends BladeCompiler
{
    use CompilesComponents;
}
