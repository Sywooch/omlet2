<?php

namespace app\controllers;

use app\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use app\models\Recipe;
use yii\helpers\Url;

class CabinetController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['kitchen','status'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['kitchen','status'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionProfile($email)
    {
        $cook = User::find()->where('email=:email', ['email' => $email])->one();
        if (empty($cook)) return $this->show404();

        $recipesProvider = new ActiveDataProvider([
                'query' => Recipe::find()->where([
                    'author' => $cook->id
                ])->andWhere([
                    'status' => Recipe::getActiveStatuses()
                ]),
                'pagination'=>['pageSize'=>12]
            ]
        );

        return $this->render('profile',[
            'cook' => $cook,
            'recipesProvider' => $recipesProvider,
        ]);
    }


    //todo-in тут ето должно быть?
    public function actionStatus($id,$status)
    {
        if (!$id || !$status) return $this->show404();
        $recipe = Recipe::findOne((int)$id);
        if (!$recipe
            || ($recipe->author != \Yii::$app->user->id)
            || \Yii::$app->user->identity->role === \app\models\User::ADMIN_ROLE
        ) return $this->show404();

        switch ($status) {
            case Recipe::STATUS_SCRATCH:
                $recipe->status = Recipe::STATUS_SCRATCH;
                break;
            case Recipe::STATUS_PUBLISHED:
                $recipe->status = Recipe::STATUS_PUBLISHED;
                break;
            case Recipe::STATUS_MODIFIED:
                $recipe->status = Recipe::STATUS_MODIFIED;
                break;
            case Recipe::STATUS_APPROVED:
                if (\Yii::$app->user->identity->role !== \app\models\User::ADMIN_ROLE)
                    return $this->show404();
                $recipe->status = Recipe::STATUS_APPROVED;
                break;
            default:return $this->show404();
        }
        $recipe->save();

        return $this->redirect(Url::to(['cabinet/kitchen']));
    }

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
            if ($user->signIn())
                return $this->redirect(Url::previous());
        } else {
            Url::remember(Url::previous());
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
                    $this->redirect(Url::previous());
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

    public function actionDelete($id)
    {
        if (!$id) return $this->show404();
        $recipe = Recipe::findOne((int)$id);
        if (!$recipe || ($recipe->author != \Yii::$app->user->id)) return $this->show404();

        $recipe->status = Recipe::STATUS_USER_DELETED;
        $recipe->save();
        return $this->redirect(Url::to(['cabinet/kitchen']));
    }

    public function actionKitchen()
    {
        $userRecipes = new ActiveDataProvider([
            'query' => Recipe::find()->where(
                'author='.(int)\Yii::$app->user->identity->id.' AND status !='.Recipe::STATUS_USER_DELETED
            ),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $savedRecipes = new ActiveDataProvider([
            'query' => \Yii::$app->user->identity->getSavedRecipes(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        $user = \Yii::$app->user->identity;
        if ($user->load(\Yii::$app->request->post())) {
            $user->update();
        }

        return $this->render('kitchen', [
            'userRecipes' => $userRecipes,
            'savedRecipes' => $savedRecipes,
            'user' => $user,
        ]);
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        $this->redirect('/');
    }

}
