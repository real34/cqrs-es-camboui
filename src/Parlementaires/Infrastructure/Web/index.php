<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';

use Parlementaires\Infrastructure\DomainAdapter;

DomainAdapter::bootstrap();
/** @var \Parlementaires\Domain\ReadModel\TotauxRepository $totauxRepository */
$totauxRepository = DomainAdapter::repository('totauxRepository');

function lienFicheParlementaire($id) {
    return sprintf(
        '<a href="http://www2.assemblee-nationale.fr/deputes/fiche/OMC_%1$s">%1$s</a>',
        $id
    );
}

?>
<h1>Ma réserve parlementaire</h1>

<div>
    <h2>Totaux par acteur</h2>
    <?php if ($totauxRepository->count() === 0) : ?>
        <p>Pas encore de données... importez moi ça !</p>
    <?php else: ?>
        <p><?= sprintf('%d acteurs ont attribué des subventions', $totauxRepository->count()); ?></p>

        <h3>Top 20</h3>
        <ul>
            <?php foreach ($totauxRepository->findTop(20) as $total) :?>
                <li>
                    <?= sprintf(
                        '%s : %d€ (%d subventions)',
                        lienFicheParlementaire($total['id']),
                        $total['total_en_euros'],
                        $total['nombre_de_subventions']
                    ); ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <h3>Flop 20</h3>
        <ul>
            <?php foreach ($totauxRepository->findFlop(20) as $total) :?>
                <li>
                    <?= sprintf(
                        '%s : %d€ (%d subventions)',
                        lienFicheParlementaire($total['id']),
                        $total['total_en_euros'],
                        $total['nombre_de_subventions']
                    ); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<aside>
    <blockquote>
        <p>
            Chaque parlementaire, de la majorité comme de l’opposition, peut proposer l’attribution de subventions
            à hauteur de 130 000 euros en moyenne, la modulation de la répartition entre les députés
            relevant de chaque groupe politique.
        </p>
        <p>
            Les membres du Bureau de l’Assemblée nationale disposent d’une réserve de 140 000 euros,
            les vice-présidents de l’Assemblée nationale, les questeurs, les présidents de groupe, les
            présidents de commission disposent de 260 000 euros, le Président de l’Assemblée nationale de 520 000 euros.
        </p>
        <cite>http://www.assemblee-nationale.fr/budget/reserve_parlementaire.asp</cite>
    </blockquote>
</aside>