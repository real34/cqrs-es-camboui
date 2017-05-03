# Atelier CQRS / Event-Sourcing

... les mains dans le camboui

## Contenu

Ce dépôt contient une base de code vous permettant de commencer à
manipuler les concepts de l'event sourcing et des architectures CQRS.

Vous y trouverez :

* des slides de présentation
* un jeu de données à exploiter
* une base d'application permettant de traiter une partie des données ... à compléter

## Lancement initial

Ci-dessous les premières étapes pour commencer à se familiariser avec l'application :

* `make install` (`composer install`)
* `make test` (et / ou `make spec`)
* `make clear_event_store` pour s'assurer de ne pas avoir de données en trop
* Import d'un jeu de données (un jeu complet et 2 partiels sont disponibles):
    * `cat data/2014_reserve_parlementaire_light_3.json | php -f src/Parlementaires/Infrastructure/Console/ImportRéserveParlementaire.php`
    * `make clear_event_store && cat data/2014_reserve_parlementaire_light_500.json | php -f src/Parlementaires/Infrastructure/Console/ImportRéserveParlementaire.php`
    * `make clear_event_store && zcat data/2014_reserve_parlementaire.json.zip | php -f src/Parlementaires/Infrastructure/Console/ImportRéserveParlementaire.php`
* Lancer l'application web `make start`

## À compléter

Afin de comprendre les concepts voici quelques tâches à accomplir :

* Ajouter une nouvelle information dans la projection `TotauxParActeurProjector` (ex: nombre de subventions accordées)
* Ajouter une nouvelle projection pour connaître la répartition selon les programmes budgétaires
* Implémenter `\Parlementaires\Domain\ValueObject\IdProgramme::guardAgainstInvalidValue()`
de manière à détecter les données incorrectes, et/ou les corriger à la volée
afin d'être plus tolérant aux erreurs
* Ajouter la gestion de l'information "SubventionAnonyme" (commande, handler, évènement, import), ou simulez d'après le
jeu de données une information "ParlementaireÉlu" avec les informations non exploitées
* Que faudrait-il modifier pour pouvoir voir la répartition des subventions données par les Acteurs du Top 20 ? Facile à faire ?
* Actuellement les projections ne "vivent pas" en temps réel car nous
utilisons un "InMemoryRepository": que faudrait-il faire pour ne rejouer
l'event store que lorsque nécessaire? Implémentez cela avec un "FileBasedRepository"
* (Avancé) En commençant par `\Parlementaires\Domain\CommandHandler\RéserveParlementaire::handleAttribuerSubvention()`
et les ressources que vous trouverez en commentaire, ajoutez une couche
de garantie de cohérence des données avec un agrégat racine (aggregate root)
qui aura pour rôle de vérifier des règles métiers que vous définirez.
Par exemple : seul un parlementaire en mandat peut faire des subventions,
le montant des subventions ne peut dépasser xxx sur une année...

## Ressources additionnelles

Pour aller plus loin, je vous recommande ces lectures :

* [un article de Martin Fowler de 2005 ](http://martinfowler.com/eaaDev/EventSourcing.html) faisant le tour du sujet de l'Event Sourcing
* pour approfondir : [une très bonne série d'articles sur la mise en place de cette architecture](https://msdn.microsoft.com/en-us/library/jj591560.aspx) (et du DDD) dans une équipe Microsoft. Très riche, et beaucoup de contenus détaillés.
* [une FAQ assez intéressante](http://cqrs.nu/) sur les questions que l'on se pose initialement au début (ou après)
* [un glossaire des termes liés au DDD](https://dddcommunity.org/resources/ddd_terms/) - les plus importants à mon sens :
    * Command,
    * (Bounded) Context,
    * Aggregate,
    * Entity,
    * Value Object
    * Immutable,
    * Invariant,
    * Repository,
    * Projection,
    * Side Effect,
    * Context Map
* et enfin deux livres référence (mais la série sur le site Microsoft est bien plus digeste pour un début)
    * [Domain-Driven Design: Tackling Complexity in the Heart of Software](https://www.amazon.fr/Domain-Driven-Design-Tackling-Complexity-Software/dp/0321125215/https://www.amazon.fr/Domain-Driven-Design-Tackling-Complexity-Software/dp/0321125215/)
    * [Implementing Domain-Driven Design](https://www.amazon.fr/Implementing-Domain-Driven-Design-Vaughn-Vernon/dp/0321834577/)

## Sources

Les jeux de données sont issus des sources suivantes :

* https://www.data.gouv.fr/fr/datasets/reserve-parlementaire/
* https://www.data.gouv.fr/fr/datasets/r-serve-parlementaire-2012-attribu-e-aux-collectivit-s-territoriales/
* http://adresse.data.gouv.fr/csv/

Merci à ceu⋅x⋅lles qui permettent de rendre ces données accessibles !

## TODO (moi)

* Licence https://twitter.com/pierremartin/status/851950047671570432
* [ ] Corriger l'extraction des adresses du JSON de 2013 (à priori format incorrect pour jq, sinon passer au CSV)
* [ ] https://www.data.gouv.fr/fr/datasets/associations/
* [ ] http://www2.assemblee-nationale.fr/reserve_parlementaire/plf/2015
* [ ] Analyser les données 2012