<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "likes".
 *
 * @property integer $user_id
 * @property integer $recipe_id
 */
class Likes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'likes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'recipe_id'], 'required'],
            [['user_id', 'recipe_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'recipe_id' => 'Recipe ID',
        ];
    }
}
