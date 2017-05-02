<?php

namespace Parlementaires\Infrastructure;

use Parlementaires\Domain\Command\AttribuerSubvention;
use Parlementaires\Domain\CommandHandler\RéserveParlementaire;
use SymfyolovelJS\CommandBus;

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

//        static::$repositories['xxxRepository'] = new xxxRepository();

//        static::$sideEffects['emailNotifications'] = new EmailNotifications();

        $domainEventsRecorder = new DomainEventsRecorder();
        $commandBus = static::makeCommandBus(
            new RéserveParlementaire(
                $domainEventsRecorder
            )
        );

//        static::$eventBus = static::makeEventBus(
//            new ExerciceProjection(static::$repositories['exerciceRepository'])
//        );

        static::$commandBus = $commandBus;
        static::$bootstraped = true;
    }

    public static function commandBus() : CommandBus
    {
        static::__guardAgainstNotBootstraped();
        return static::$commandBus;
    }

//    public static function eventBus() : xxx
//    {
//        static::__guardAgainstNotBootstraped();
//        return static::$eventBus;
//    }

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

//    private static function makeEventBus(
//        XXXProjection $xxxProjection,
//    ) : xxx
//    {
//        $eventBus = new xxx();
//        return $eventBus;
//    }

//    public static function dropProjections()
//    {
//        static::__guardAgainstNotBootstraped();
//
//        foreach (static::$repositories as $repository) {
//            try {
//                $repository->truncate();
//            } catch (\Exception $e) {
//                static::log($e->getMessage(), LogLevel::ERROR);
//            }
//        }
//    }

//    public static function startReplayMode()
//    {
//        static::$isReplay = true;
//        foreach (static::$sideEffects as $sideEffect) {
//            $sideEffect->startReplayMode();
//        }
//    }
//
//    public static function isReplay()
//    {
//        return (bool) static::$isReplay;
//    }
}