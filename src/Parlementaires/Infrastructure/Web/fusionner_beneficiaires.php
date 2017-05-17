<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';

use Parlementaires\Domain\ValueObject\Bénéficiaire;
use Parlementaires\Infrastructure\DomainAdapter;

DomainAdapter::bootstrap();
$commandBus = DomainAdapter::commandBus();

try {
    $bénéficiaireCanonique = new Bénéficiaire(
        $_POST['canonique_nom'] ?? '',
        $_POST['canonique_addresse'] ?? ''
    );
    $bénéficiaireAFusionner = new Bénéficiaire(
        $_POST['a_fusionner_nom'] ?? '',
        $_POST['a_fusionner_addresse'] ?? ''
    );

    // TODO Implémenter FusionnerBénéficiaires
    $command = new FusionnerBénéficiaires($bénéficiaireCanonique, $bénéficiaireAFusionner);
    $commandBus->handle($command);
    header('Location: index.php');
} catch (\Exception $e) {
    printf('Oops, une erreur est survenue: %s', $e->getMessage());
}

/**
 Exemple d'usage:

<form action="fusionner_beneficiaires.php" method="post">
    <input type="text" name="canonique_nom" />
    <input type="text" name="canonique_addresse" />
    <hr>

    <input type="text" name="a_fusionner_nom" />
    <input type="text" name="a_fusionner_addresse" />

    <input type="submit" value="Fusionner les bénéficiaires" />
</form>
 */