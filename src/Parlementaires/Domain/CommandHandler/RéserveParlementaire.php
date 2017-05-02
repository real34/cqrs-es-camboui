<?php

namespace Parlementaires\Domain\CommandHandler;

use Parlementaires\Domain\Command\AttribuerSubvention;
use Parlementaires\Domain\Event\Recorder;
use Parlementaires\Domain\Event\SubventionAttribuée;

class RéserveParlementaire
{
    private $eventRecorder;

    public function __construct(Recorder $eventRecorder)
    {
        $this->eventRecorder = $eventRecorder;
    }

    public function handleAttribuerSubvention(AttribuerSubvention $command)
    {
//        TODO Créer un agrégat avec la logique qui va bien (votre but ultime chez vous)
//        @see https://github.com/broadway/broadway/blob/02e0d40335df855f5f0648a95751c48dd35003af/examples/event-sourced-domain-with-tests/Invites.php
//        @see https://github.com/broadway/broadway/blob/0a89c4345b22ba0ed3f3ac5cc7a6f4beea41a020/examples/event-sourced-child-entity/Parts.php
//        @see https://github.com/broadway/broadway/blob/master/src/Broadway/EventSourcing/EventSourcingRepository.php
//        @see https://github.com/yreynhout/AggregateSource/blob/master/src/Core/AggregateSource/AggregateBuilder.cs
//        Principe:
//          Un "aggregate root" est responsable/garant du respect
//          d'un ensemble de conditions métiers et de la cohérence des données
//          C'est le SEUL point d'entrée pour toute mutation métier sur
//          un sous-ensemble (exclusif) des autres aggrégats / entités
//
//        Lorsqu'il est Event Sourcé, il est construit de zéro durant le load()
//        en créant un object vide, auquel on réapplique tous les évènements
//        le concernant (aggregate + id). On a ainsi son état à un instant "t"
//        $acteur = $this->acteurRepository->load($command->getIdActeur());
//
//        Le CommandHandler peut alors effectuer la/les opérations nécessaires
//        sur l'aggrégat. Cela a pour effet de muter en interne l'état de celui-ci
//        tout en remplissant une liste de "uncommitedEvents" (évènements métiers
//        non encore persistés, depuis l'instant "t" du load())
//        $acteur->attribueSubvention();
//
//        C'est lors du save() que le repository va persister les évènements
//        non commités en fin d'event store. Pour cela il va vérifier qu'aucun
//        nouvel évènement concernant cet agrégat racine n'a été ajouté depuis
//        l'instant "t". Si c'est le cas (assez rare), il peut être suffisant
//        de lever une Exception (cf https://github.com/gregoryyoung/m-r/blob/31d315faf272182d7567a038bbe832a73b879737/SimpleCQRS/EventStore.cs#L54)
//        ou d'essayer de sauver les meubles (cf https://github.com/broadway/broadway/blob/02e0d40335df855f5f0648a95751c48dd35003af/test/Broadway/EventStore/ConflictResolvingEventStoreTest.php#L23)
//        ... sinon documentez vous sur la gestion de la concurrence dans les architectures
//        CQRS et faites vous votre opinion par rapport à votre contexte !
//        $this->acteurRepository->save($acteur);

        $event = new SubventionAttribuée(
            $command->getIdActeur(),
            $command->getBénéficiaire(),
            $command->getMontant(),
            $command->getIdProgramme(),
            $command->getDescriptif()
        );
        $this->eventRecorder->record($event);
    }
}