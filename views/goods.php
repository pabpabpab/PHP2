<?php
/**
 * @var \App\models\Good[] $goods
 * @var $pagesQuantity
 */
?>

<h1>Товары</h1>
<?php foreach ($goods as $good) :?>
    <p>
        <a href="/?c=good&a=one&id=<?= $good->id ?>">
            <?= $good->name ?>
        </a>
    </p>
<?php endforeach;?>

<p>
    <?php for ($i = 1; $i <= $pagesQuantity; $i++) :?>
        <a href="/?c=good&a=all&page=<?= $i ?>" class="page_link">
            <?= $i ?>
        </a>
    <?php endfor;?>
</p>
