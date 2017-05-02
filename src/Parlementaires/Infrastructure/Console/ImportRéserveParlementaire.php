<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';

use Parlementaires\Domain\Command\AttribuerSubvention;
use Parlementaires\Domain\ValueObject\Bénéficiaire;
use Parlementaires\Domain\ValueObject\IdActeur;
use Parlementaires\Domain\ValueObject\IdProgramme;
use Parlementaires\Domain\ValueObject\Monnaie;
use Parlementaires\Infrastructure\DomainAdapter;

$data = '[
  {
    "Bénéficiaire": "Mieux se déplacer à Bicyclette",
    "Adresse": "MDB –\n37 boulevard Bourdon\n75004 Paris",
    "Descriptif": "Réalisation des supports de communication pour faire connaître ses nouvelles dispositions sur l\'usage de la bicyclette et diffusion",
    "Montant": 4000,
    "Nom": "BAUPIN",
    "Prénom": "Denis",
    "Département": "Paris",
    "Groupe": "Ecolo",
    "Programme budgétaire": "203-15",
    "ID_Acteur": "PA609016"
  },
  {
    "Bénéficiaire": "Fjt les Oiseaux",
    "Adresse": "48, rue des Cras\n25000 Besançon",
    "Descriptif": "implantation d’une plate-forme de compostage collectif accéléré",
    "Montant": 4000,
    "Nom": "ALAUZET",
    "Prénom": "Éric",
    "Département": "Doubs",
    "Groupe": "Ecolo",
    "Programme budgétaire": "113-07",
    "ID_Acteur": "PA605963"
  }
]';
$_ENV['PARL_SOURCE'] = 'hardcoded_data';

$error = fopen('php://stderr', 'w+');
$out = fopen('php://stdout', 'w+');
fwrite($out, '---------- IMPORT DES RÉSERVES PARLEMENTAIRES --------' . PHP_EOL);
fwrite($out, 'Je vais importer des données ...' . PHP_EOL);

$toImport = json_decode($data, true);
fwrite($out, sprintf(
    '... %d entrée(s) à importer' . PHP_EOL,
    count($toImport)
));

DomainAdapter::bootstrap();
$commandBus = DomainAdapter::commandBus();

foreach ($toImport as $i => $subvention) {
    try {
        $command = new AttribuerSubvention(
            new IdActeur($subvention['ID_Acteur']),
            new Bénéficiaire(
                $subvention['Bénéficiaire'],
                $subvention['Adresse']
            ),
            Monnaie::EUR($subvention['Montant']),
            new IdProgramme($subvention['Programme budgétaire']),
            $subvention['Descriptif']
        );
        $commandBus->handle($command);
    } catch (\RuntimeException $e) {
        fwrite(
            $error,
            sprintf('[%d] Erreur: %s' . PHP_EOL, $i, $e->getMessage())
        );
    }
}

fwrite($out, '... mission accomplie ;)' . PHP_EOL);