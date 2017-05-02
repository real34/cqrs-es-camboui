# Parlementaires

Ce namespace contient le coeur de votre application. C'est lui qui est responsable d'enregistrer
les dépenses de nos parlementaires et de permettre à tou⋅te⋅s de comprendre ce qu'il se passe.

Il est organisé en 2 parties distinctes :

* `Domain` : la partie métier de votre application. C'est la valeur ajoutée de votre métier, ce namespace
    contient des classes PHP "basiques" représentant _votre_ compréhension du métier à un instant _t_
* `Infrastructure` : la couche infrastructure de votre application vous permet de faire le lien entre
    le code métier et l'implémentation des dépendances externes (stockage, requêtes web ...). Cela
    consistera donc principalement à implémenter les interfaces définies dans `Domain` en utilisant
    votre caisse à outil préférée : `SymfyolovelJS`