<?php

namespace Spatie\BladeX\Tests\Features\ViewModel\TestClasses;

use Spatie\BladeX\ViewModel;

class DummyViewModel extends ViewModel
{
    public $property = 'abc';

    public function __construct()
    {
        // This one is here for testing purposes
    }

    public function post(): \stdClass
    {
        return (object) [
            'title' => 'title',
            'body' => 'body',
        ];
    }

    public function categories(): array
    {
        return [
            (object) [
                'name' => 'category A',
            ],
            (object) [
                'name' => 'category B',
            ],
        ];
    }

    public function callableMethod(string $name): string
    {
        return $name;
    }
}
