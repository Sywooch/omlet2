<?php
namespace app\controllers;


class UploadController extends \yii\web\Controller
{
    public function actionPhoto()
    {
        if (!\Yii::$app->request->isAjax) {
            exit();
        }
        $response = [];
        $photoNum = (int)\Yii::$app->request->post('photoNum');
        $recipeId = (int)\Yii::$app->request->post('recipeId');
        $mainPhotoFlag = \Yii::$app->request->post('mainPhotoFlag');

        if (!$recipeId || !$mainPhotoFlag) {
            $response['error'] = ['no need data attached'];
            echo json_encode($response);
            exit();
        }
        if ($mainPhotoFlag == 'true') {
            $mainPhoto = new \app\models\UploadMainPhoto();
            $mainPhoto->photo = \yii\web\UploadedFile::getInstance($mainPhoto, 'photo');
            if ($mainPhoto->upload($recipeId)) {
                //может нужно чтоо отдать клиенту?
            } else {
                $response['error'] = serialize($mainPhoto->getErrors());
            }
        } elseif ($mainPhotoFlag == 'false') {
            if (!$photoNum) {
                $response['error'] = ['no need data attached'];
                echo json_encode($response);
                exit();
            }
            //обработка не главных фото
            $stepPhoto = new \app\models\UploadStepPhoto();
            $stepPhoto->photo = \yii\web\UploadedFile::getInstance($stepPhoto, 'photo');
            if ($stepPhoto->upload($recipeId, $photoNum)) {
                //может нужно чтоо отдать клиенту?
            } else {
                $response['error'] = serialize($stepPhoto->getErrors());
            }
        }
        echo json_encode($response);
        exit();
    }
}