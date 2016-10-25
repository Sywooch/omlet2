<?php

namespace app\controllers;

use app\models\User;


class CabinetController extends \yii\web\Controller
{


    public function actionIndex()
    {
        $this->redirect('/cabinet/enter');
    }

    public function actionEnter()
    {
        return $this->render('enter');
    }

    public function actionLogin()
    {
        if (\Yii::$app->request->post()) {
            $user = new User();
            $user->load(\Yii::$app->request->post());
            if ($user->signIn()) {
                //redirect to need url
               return $this->redirect('/cabinet/kitchen');
            }
        }
        $this->redirect('/cabinet/enter');
    }

    public function actionRegistration()
    {
        if (\Yii::$app->request->post()) {
            if ((\Yii::$app->request->post('User')['password'] == \Yii::$app->request->post('User')['password2'])) {
                $user = new User();
                $user->load(\Yii::$app->request->post());
                if ($user->signUp()) {
                    //redirect to need url
                    $this->redirect('/cabinet/kitchen');
                } else {
                    \Yii::$app->session->set('registration',1);
                    $this->redirect('/cabinet/enter');
                }
            } else {
                \Yii::$app->session->set('registration',1);
                \Yii::$app->session->setFlash('login_error', 'Паролі не співпадають...');
                $this->redirect('/cabinet/enter');
            }

        }
    }

    public function actionKitchen()
    {
        return $this->render('kitchen');
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        $this->redirect('/');
    }

}
