<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/style.css?t=<?= time() ?>">
    <title>Document</title>
</head>
<body>
    <ul>
        <li><a href="/?c=user&a=all">Пользователи</a></li>
        <li><a href="/?c=good&a=all">Товары</a></li>
        <li><a href="/?c=good&a=add">Добавить товар</a></li>
    </ul>

    <?php if (!empty($msg)): ?>
        <div class='error'><?= $msg ?></div>
    <?php endif; ?>

    <?= $content ?>
</body>
</html>