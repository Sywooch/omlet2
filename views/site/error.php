<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = 'Такої сторінки не знайдено...';
?>
<div class="site-error">

    <?php
    if (!IS_DEV) { ?>
        <div class="col-xs-12 col-md-6">
            <h1>Такої сторінки не знайдено...</h1>
        </div>
        <div class="col-xs-12 col-md-6">
            <img src="<?= $imageUrl ?>" title="лучшие рецепты на <?= HOST ?>" alt="лучшие рецепты на <?= HOST ?>" />
        </div>
    <?php } elseif (empty($exception))  { ?>
        <div class="col-xs-12 col-md-6">
            <h1>Такої сторінки не знайдено...</h1>
        </div>
        <div class="col-xs-12 col-md-6">
            <img src="<?= $imageUrl ?>" title="лучшие рецепты на <?= HOST ?>" alt="лучшие рецепты на <?= HOST ?>" />
        </div>
    <?php } else { ?>
        <pre>
            <?php
            var_dump($exception);
            ?>
        </pre>
    <?php } ?> ?>

</div>
