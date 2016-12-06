<?php
namespace app\models;

use abeautifulsite\SimpleImage;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadMainPhoto extends Model
{
    /**
     * @var UploadedFile
     */
    public $photo;

    public function rules()
    {
        return [
            [['photo'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload($recipeId)
    {
        if ($this->validate()) {
            $filePath = \Yii::getAlias('@app') . DS .  'media' . DS .  $recipeId;
            $filePath .= DS . '0.jpg';

            //saving photo
            $image = new SimpleImage($this->photo->tempName);
            if ( $image->get_original_info()['width']>650 || $image->get_original_info()['height']>500)
                $image->adaptive_resize(650, 500);
            $image->save($filePath);

            return true;
        } else {
            return false;
        }
    }
}