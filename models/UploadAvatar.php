<?php
namespace app\models;

use abeautifulsite\SimpleImage;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadAvatar extends Model
{
    /**
     * @var UploadedFile
     */
    public $photo;

    public function rules()
    {
        return [
            [['photo'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function upload($userId)
    {
        if ($this->validate()) {
            $filePath = \Yii::getAlias('@app') . DS .  'media' . DS .  'avatars' . DS. (int)$userId . '.jpg';
            //saving photo
            $image = new SimpleImage($this->photo->tempName);
            $image->adaptive_resize(300, 360)->save($filePath);

            return true;
        } else {
            return false;
        }
    }
}