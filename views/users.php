<?php
/**
 * @var \App\models\User[] $users
 */
?>

<h1>Пользователи</h1>
<?php foreach ($users as $user) :?>
    <p>
        <a href="/?c=user&a=one&id=<?= $user->id ?>">
            <?= $user->name ?>
        </a>
    </p>
<?php endforeach;?>
