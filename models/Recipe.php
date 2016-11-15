<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recipe".
 *
 * @property integer $id
 * @property integer $section
 * @property string $name
 * @property string $alias
 * @property string $description
 * @property string $add_date
 * @property string $edit_date
 * @property integer $status
 * @property integer $author
 * @property integer $likes
 * @property integer $views
 * @property integer $reputation
 * @property string $last_view_date
 *
 * @property Ingridient[] $ingridients
 * @property Instruction[] $instructions
 * @property SavedRecipe[] $savedRecipes
 * @property User[] $users
 * @property Tags[] $tags
 */
class Recipe extends \yii\db\ActiveRecord
{
    const STATUS_SCRATCH = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_APPROVED = 3;
    const STATUS_MODIFIED = 4;
    const STATUS_USER_DELETED = 5;


    public static function getStatusTranslate($status)
    {
        $translate = [
            self::STATUS_SCRATCH => 'Чернетка',
            self::STATUS_PUBLISHED => 'Опублікований',
            self::STATUS_APPROVED => 'Опублікований',
            self::STATUS_MODIFIED => 'Опублікований',
        ];
        if (empty($translate[$status])) return '';
        return $translate[$status];
    }

    public static function getActiveStatuses()
    {
        return [self::STATUS_PUBLISHED, self::STATUS_APPROVED, self::STATUS_MODIFIED];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'recipe';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section', 'name', 'status', 'author'], 'required'],
            [['section', 'status', 'author', 'likes', 'views', 'reputation'], 'integer'],
            [['add_date', 'edit_date', 'last_view_date'], 'safe'],
            [['name', 'alias'], 'string', 'max' => 150],
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'section' => 'Section',
            'name' => 'Name',
            'alias' => 'Alias',
            'description' => 'Description',
            'add_date' => 'Add Date',
            'edit_date' => 'Edit Date',
            'status' => 'Status',
            'author' => 'Author',
            'likes' => 'Likes',
            'views' => 'Views',
            'reputation' => 'Reputation',
            'last_view_date' => 'Last View Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngridients()
    {
        return $this->hasMany(Ingridient::className(), ['recipe_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstructions()
    {
        return $this->hasMany(Instruction::className(), ['recipe_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSavedRecipes()
    {
        return $this->hasMany(SavedRecipe::className(), ['recipe_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('saved_recipe', ['recipe_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tags::className(), ['recipe_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasMany(RecipeSection::className(), ['id' => 'section']);
    }
}
