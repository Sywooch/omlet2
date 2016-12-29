<?php

namespace app\controllers;

use yii\SettingsTrait;
use yii;

class AdminController extends \yii\web\Controller
{
    use SettingsTrait;

    public function getSettingsCategory()
    {
        return 'general';
    }

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->is_moderator !== \app\models\User::ADMIN_ROLE)
            return $this->goHome();

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
