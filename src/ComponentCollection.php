<?php

namespace Spatie\BladeX;

use Spatie\BladeX\Component;
use Spatie\BladeX\Laravel\Collection;

/**
 * @property-read Component $each
 */
class ComponentCollection extends Collection
{
    public function prefix(string $prefix)
    {
        $this->each(function (Component $component) use ($prefix) {
            $component->prefix($prefix);
        });

        return $this;
    }

    public function withoutNamespace()
    {
        $this->each(function (Component $component) {
            $component->withoutNamespace();
        });

        return $this;
    }
}
