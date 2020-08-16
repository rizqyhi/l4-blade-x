<?php

namespace Spatie\BladeX\Tests\SnapshotAssertions;

use PHPUnit\Framework\ExpectationFailedException;
use \ReflectionClass;
use \ReflectionObject;
use Spatie\BladeX\Tests\SnapshotAssertions\Drivers\XmlDriver;

trait MatchesSnapshots
{
    // use SnapshotDirectoryAware, SnapshotIdAware;

    protected $snapshotIncrementor = 0;

    protected $snapshotChanges = [];

    public function assertMatchesXmlSnapshot($actual): void
    {
        $this->doSnapshotAssertion($actual, new XmlDriver());
    }

    protected function doSnapshotAssertion($actual, Driver $driver)
    {
        $this->snapshotIncrementor++;

        $snapshot = Snapshot::forTestCase(
            $this->getSnapshotId(),
            $this->getSnapshotDirectory(),
            $driver
        );

        if (! $snapshot->exists()) {
            $this->assertSnapshotShouldBeCreated($snapshot->filename());

            $this->createSnapshotAndMarkTestIncomplete($snapshot, $actual);
        }

        if ($this->shouldUpdateSnapshots()) {
            try {
                // We only want to update snapshots which need updating. If the snapshot doesn't
                // match the expected output, we'll catch the failure, create a new snapshot and
                // mark the test as incomplete.
                $snapshot->assertMatches($actual);
            } catch (ExpectationFailedException $exception) {
                $this->updateSnapshotAndMarkTestIncomplete($snapshot, $actual);
            }

            return;
        }

        try {
            $snapshot->assertMatches($actual);
        } catch (ExpectationFailedException $exception) {
            $this->rethrowExpectationFailedExceptionWithUpdateSnapshotsPrompt($exception);
        }
    }

    protected function assertSnapshotShouldBeCreated(string $snapshotFileName): void
    {
        if ($this->shouldCreateSnapshots()) {
            return;
        }

        $this->fail(
            "Snapshot \"$snapshotFileName\" does not exist.\n".
            'You can automatically create it by removing '.
            '`-d --without-creating-snapshots` of PHPUnit\'s CLI arguments.'
        );
    }

    protected function createSnapshotAndMarkTestIncomplete(Snapshot $snapshot, $actual): void
    {
        $snapshot->create($actual);

        $this->registerSnapshotChange("Snapshot created for {$snapshot->id()}");
    }

    protected function registerSnapshotChange(string $message): void
    {
        $this->snapshotChanges[] = $message;
    }

    protected function shouldUpdateSnapshots(): bool
    {
        return in_array('--update-snapshots', $_SERVER['argv'], true);
    }

    protected function rethrowExpectationFailedExceptionWithUpdateSnapshotsPrompt($exception): void
    {
        $newMessage = $exception->getMessage()."\n\n".
            'Snapshots can be updated by passing '.
            '`-d --update-snapshots` through PHPUnit\'s CLI arguments.';

        $exceptionReflection = new ReflectionObject($exception);

        $messageReflection = $exceptionReflection->getProperty('message');
        $messageReflection->setAccessible(true);
        $messageReflection->setValue($exception, $newMessage);

        throw $exception;
    }

    protected function getSnapshotId(): string
    {
        return (new ReflectionClass($this))->getShortName().'__'.
            $this->getName().'__'.
            $this->snapshotIncrementor;
    }

    protected function getSnapshotDirectory(): string
    {
        return dirname((new ReflectionClass($this))->getFileName()).
            DIRECTORY_SEPARATOR.
            '__snapshots__';
    }
}
