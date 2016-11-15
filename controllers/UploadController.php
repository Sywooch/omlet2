<?php
namespace app\controllers;


use app\models\User;

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

    public function actionAvatar()
    {
        if (!\Yii::$app->request->isAjax) {
            exit();
        }
        $response = [];
        $userId = (int)\Yii::$app->request->post('user_id');

        if (!$userId) {
            $response['error'] = ['no need data attached'];
            echo json_encode($response);
            exit();
        }

        $avatar = new \app\models\UploadAvatar();
        $avatar->photo = \yii\web\UploadedFile::getInstance($avatar, 'photo');
        if ($avatar->upload($userId)) {
            $user = User::findOne((int)$userId);
            $user->avatar_status = User::AVATAR_PRESENT;
            $user->save();
        } else {
            $response['error'] = serialize($avatar->getErrors());
        }

        echo json_encode($response);
        exit();
    }
}