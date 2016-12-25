<?php
/**
 * var \app\models\User $cook
 */
use \yii\helpers\Url;

?>
<br />
<div class="row">
    <div class="col-xs-12 col-md-4">
        <?php
        echo
        file_exists(\Yii::getAlias('@app').DS.'media'.DS.'avatars'.DS.$cook->id.'.jpg')?
            '<img src="'. Url::to(['image/avatar', 'id' => $cook->id]).'"" class="img-rounded">':
            ''
        ?>
    </div>
    <div class="col-xs-12 col-md-4">
        <table class="table">
            <tbody>
                <tr>
                    <td><b>Шеф</b></td>
                    <td><?= $cook->username ?></td>
                </tr>
                <tr>
                    <td><b>Контакти</b></td>
                    <td><?= $cook->email ?></td>
                </tr>
                <tr>
                    <td><b>Про шефа</b></td>
                    <td><?= $cook->about_me ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="hidden-xs col-md-4">
        <img src="/web/img/profile-back.png" title="лучшие повары на <?= HOST ?>"  alt="лучшие повары на <?= HOST ?>" />
    </div>
</div>
<div class="row">
    <h1>Рецепти від шефа:</h1>
</div>
<div class="row">
    <?php
    echo \yii\widgets\ListView::widget([
        'dataProvider' => $recipesProvider,
        'itemView' => DS . '..' . DS . 'search' . DS . '_recipe',
        'summary' => '{count} з {totalCount} рецептів'
    ]);

    ?>
</div>
