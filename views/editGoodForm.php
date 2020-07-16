<?php
/** @var \App\models\Good $good */
?>

<h1>Редактировать товар</h1>
<form enctype='multipart/form-data' method='post' action='/?c=good&a=save&id=<?= $good->id ?>'>
    <input type='text' placeholder='наименование товара' name='name' value=<?= $good->name ?> class='field'><br><br>
    <input type='text' placeholder='цена товара' name='price' value=<?= $good->price ?> class='field'><br><br>
    <textarea placeholder='описание товара' name='info' class='field'><?= $good->info ?></textarea><br><br>
    <input type='submit' name='addproduct' class='field'>
</form>