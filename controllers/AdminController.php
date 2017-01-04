<?php

namespace app\controllers;

use Yii;
use app\models\Setting;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminController implements the CRUD actions for Setting model.
 */
class AdminController extends Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== \app\models\User::ADMIN_ROLE)
            return $this->goHome();

        return parent::beforeAction($action);
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Setting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Setting::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Setting model.
     * @param string $category
     * @param string $key
     * @return mixed
     */
    public function actionView($category, $key)
    {
        return $this->render('view', [
            'model' => $this->findModel($category, $key),
        ]);
    }

    /**
     * Creates a new Setting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Setting();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'category' => $model->category, 'key' => $model->key]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Setting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $category
     * @param string $key
     * @return mixed
     */
    public function actionUpdate($category, $key)
    {
        $model = $this->findModel($category, $key);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'category' => $model->category, 'key' => $model->key]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Setting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $category
     * @param string $key
     * @return mixed
     */
    public function actionDelete($category, $key)
    {
        $this->findModel($category, $key)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Setting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $category
     * @param string $key
     * @return Setting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($category, $key)
    {
        if (($model = Setting::findOne(['category' => $category, 'key' => $key])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
