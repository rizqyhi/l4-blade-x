<?php

namespace Spatie\BladeX\Tests\SnapshotAssertions\Drivers;

use \DOMDocument;
use PHPUnit\Framework\Assert;
use Spatie\BladeX\Tests\SnapshotAssertions\Driver;
use Spatie\BladeX\Tests\SnapshotAssertions\Exceptions\CantBeSerialized;

class XmlDriver implements Driver
{
    public function serialize($data): string
    {
        if (! is_string($data)) {
            throw new CantBeSerialized('Only strings can be serialized to xml');
        }

        $domDocument = new DOMDocument('1.0');
        $domDocument->preserveWhiteSpace = false;
        $domDocument->formatOutput = true;

        $domDocument->loadXML($data);

        return $domDocument->saveXML();
    }

    public function extension(): string
    {
        return 'xml';
    }

    public function match($expected, $actual)
    {
        Assert::assertXmlStringEqualsXmlString($expected, $actual);
    }
}
