<?php

namespace Spatie\BladeX\Tests\Features\Context\TestClasses;

use Spatie\BladeX\ViewModel;

class UserProviderViewModel extends ViewModel
{
    public function user(): \stdClass
    {
        return (object) [
            'name' => 'Sebastian',
        ];
    }
}
