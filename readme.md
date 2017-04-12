# Atelier CQRS / Event-Sourcing

... les mains dans le camboui

## Contenu

Ce dépôt contient une base de code vous permettant de commencer à
manipuler les concepts de l'event sourcing et des architectures CQRS.

Vous y trouverez :

* des slides de présentation
* un jeu de données à exploiter
* une base d'application permettant de traiter une partie des données ... à compléter

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

## TODO

* Licence https://twitter.com/pierremartin/status/851950047671570432
* [ ] Corriger l'extraction des adresses du JSON de 2013 (à priori format incorrect pour jq, sinon passer au CSV)
* [ ] Analyser les données 2012