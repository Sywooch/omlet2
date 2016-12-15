<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="/web/js/common.js"></script>


    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <link rel="shortcut icon" href="/web/img/favicon.ico" type="image/x-icon">

    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">

    <!-- HEADER-LINE -->

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">
                    <img id="logo" src="/web/img/logo.png">
                </a>
                <form class="navbar-form navbar-right" method="get" action="<?= Url::to(['search/search']) ?>">
                    <input id="query" type="text" class="form-control" placeholder="Пошук..." name="s" maxlength="40">
                </form>
            </div>

            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->is_moderator === \app\models\User::ADMIN_ROLE) { ?>
                        <li><a href="<?=Url::to(['search/index'])?>">Рецепти</a></li>
                        <li><a href="<?=Url::to(['category/index'])?>">Категорії</a></li>
                    <?php } ?>
                    <li><a href="<?=Url::to(['search/index'])?>">Рецепти</a></li>
                    <li><a href="<?=Url::to(['recipe/add'])?>"><span class="glyphicon glyphicon-plus"  aria-hidden="true"></span> Додати рецепт</a></li>
                    <li><a href="<?=(Yii::$app->user->isGuest)? Url::to(['cabinet/enter']): Url::to(['cabinet/kitchen'])?>"><span class="glyphicon glyphicon-user"  aria-hidden="true"></span> Кабінет</a></li>
                    <?php
                    if (!Yii::$app->user->isGuest) { ?>
                        <li><a href="/cabinet/logout">Вийти</a></li>
                    <?php } ?>
                </ul>

            </div>
        </div>
    </nav>

    <!-- END OF HEADER-LINE -->


    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <br id="br" style="display:none">
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; omlet.kiev.ua 2016</p>
    </div>
</footer>
<!-- AUTH DIALOG -->
<script>
    themeAdaptation();
    $(window).resize(function(){
        themeAdaptation();
    });

    function themeAdaptation(){
        if (window.innerWidth < 768) {
            $('#br').css('display', 'block');
            if (!$('#logo').hasClass('logo-adapted')) $('#logo').addClass('logo-adapted');
        } else {
            $('#br').css('display', 'none');
            if ($('#logo').hasClass('logo-adapted')) $('#logo').removeClass('logo-adapted');
        }
    }
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
