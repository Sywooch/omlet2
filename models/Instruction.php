<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "instruction".
 *
 * @property string $id
 * @property string $recipe_id
 * @property integer $step
 * @property string $instruction
 *
 * @property Recipe $recipe
 */
class Instruction extends \yii\db\ActiveRecord
{

    public function beforeSave($insert)
    {
        return parent::beforeSave($insert);
        $this->instruction = trim($this->instruction);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'instruction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recipe_id', 'step'], 'required'],
            [['recipe_id', 'step'], 'integer'],
            [['instruction'], 'string', 'max' => 1000],
            [['recipe_id'], 'exist', 'skipOnError' => true, 'targetClass' => Recipe::className(), 'targetAttribute' => ['recipe_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'recipe_id' => 'Recipe ID',
            'step' => 'Step',
            'instruction' => 'Instruction',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipe()
    {
        return $this->hasOne(Recipe::className(), ['id' => 'recipe_id']);
    }
}
