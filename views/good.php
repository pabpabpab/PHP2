<?php
/** @var \App\models\Good $good */
?>

<h1>Товар</h1>
<p>
    Название: <?= $good->name ?> <br>
    Цена: <?= $good->price ?> <br>
    Описание: <?= $good->info ?> <br>
</p>

<a href="/?c=good&a=edit&id=<?= $good->id ?>">Редактировать</a><br>
<a href="/?c=good&a=delete&id=<?= $good->id ?>">Удалить</a>
