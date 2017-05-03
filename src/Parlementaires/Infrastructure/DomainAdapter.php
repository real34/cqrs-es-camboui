<?php

namespace Parlementaires\Infrastructure;

use Parlementaires\Domain\Command\AttribuerSubvention;
use Parlementaires\Domain\CommandHandler\RéserveParlementaire;
use Parlementaires\Domain\Event\SubventionAttribuée;
use Parlementaires\Domain\ReadModel\TotauxParActeurProjector;
use Parlementaires\Domain\Tests\ReadModel\Support\InMemoryGenericRepository;
use SymfyolovelJS\CommandBus;
use SymfyolovelJS\EventBus;

/**
 * C'est la partie caca du cablâge !
 * Mais cela est à mon avis suffisant pour débuter (ou plus si affinités)
 *
 * Ajoutez-y la complexité qu'il vous faut si vous en ressentez le besoin ...
 * (DI, Autoconfiguration, meta-programmation, Big Data, #serverless et tout ça)
 */
class DomainAdapter
{
    private static $bootstraped = false;
    private static $commandBus;
    private static $eventBus;
    private static $repositories;
    private static $isReplay = false;
    private static $sideEffects = [];

    public static function bootstrap()
    {
        if (static::$bootstraped) {
            throw new \RuntimeException('The domain has already been bootstraped');
        }

        // The InMemoryGenericRepository is not for production use, hence its "Tests" namespace ;)
        static::$repositories['totauxRepository'] = new InMemoryGenericRepository();

//        static::$sideEffects['emailNotifications'] = new EmailNotifications();

        $eventStore = new FileBasedEventStore(
            realpath(__DIR__ . '/../../../data/eventstore')
        );

        $domainEventsRecorder = new DomainEventsRecorder(
            $eventStore
        );
        $commandBus = static::makeCommandBus(
            new RéserveParlementaire(
                $domainEventsRecorder
            )
        );

        static::$eventBus = static::makeEventBus(
            new TotauxParActeurProjector(static::$repositories['totauxRepository'])
        );

        static::$commandBus = $commandBus;
        static::$bootstraped = true;

        // Since we only have in memory repositories for now, the state
        // is not kept upon each boostrap so we are forcing a replay here
        // TODO Improve it to use persistent read models
        static::regenerateProjectionsFrom($eventStore);
    }

    public static function commandBus() : CommandBus
    {
        static::__guardAgainstNotBootstraped();
        return static::$commandBus;
    }

    public static function repository($name)
    {
        static::__guardAgainstNotBootstraped();
        if (!array_key_exists($name, static::$repositories)) {
            throw new \OutOfBoundsException(sprintf(
                'No repository "%s" registered during bootstrap',
                $name
            ));
        }
        return static::$repositories[$name];
    }

    private static function __guardAgainstNotBootstraped()
    {
        if (!static::$bootstraped) {
            throw new \RuntimeException('The domain has not been bootstraped yet!');
        }
    }

    private static function makeCommandBus(
        RéserveParlementaire $reserveParlementaireCommandHandler
    ) : CommandBus
    {
        $commandBus = CommandBus::createWithHandlers([
            AttribuerSubvention::class => [$reserveParlementaireCommandHandler, 'handleAttribuerSubvention']
        ]);

        return $commandBus;
    }

    private static function makeEventBus(
        TotauxParActeurProjector $totauxParActeurProjector
    ) : EventBus
    {
        $eventBus = EventBus::createWithHandlers([
            SubventionAttribuée::class => [
                [$totauxParActeurProjector, 'handleSubventionAttribuée']
            ]
        ]);
        return $eventBus;
    }

    private static function regenerateProjectionsFrom(EventStore $eventStore) {
        static::startReplayMode();
        static::dropProjections();
        static::replayEvents($eventStore->allEvents());
    }

    public static function dropProjections()
    {
        static::__guardAgainstNotBootstraped();

        foreach (static::$repositories as $repository) {
            try {
                $repository->truncate();
            } catch (\Exception $e) {
                static::log($e->getMessage(), LogLevel::ERROR);
            }
        }
    }

    private static function replayEvents(\Iterator $events)
    {
        foreach ($events as $event) {
            static::$eventBus->handle($event);
        }
    }

    public static function startReplayMode()
    {
        static::$isReplay = true;
        foreach (static::$sideEffects as $sideEffect) {
            $sideEffect->startReplayMode();
        }
    }

    public static function isReplay()
    {
        return (bool) static::$isReplay;
    }
}