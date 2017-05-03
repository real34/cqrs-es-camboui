<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';

use Parlementaires\Infrastructure\DomainAdapter;

DomainAdapter::bootstrap();
/** @var \Parlementaires\Domain\ReadModel\GenericRepository $totauxRepository */
$totauxRepository = DomainAdapter::repository('totauxRepository');
?>
<h1>Ma réserve parlementaire</h1>

<div>
    <h2>Totaux par acteur</h2>
    <?php if ($totauxRepository->count() === 0) : ?>
        <p>Pas encore de données... importez moi ça !</p>
    <?php else: ?>
        <p><?= sprintf('%d acteurs ont attribué des subventions', $totauxRepository->count()); ?></p>
        <ul>
            <?php foreach ($totauxRepository->findAll() as $total) :?>
                <li><?= sprintf('%s : %d€', $total['id'], $total['total_en_euros']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>