<?php

namespace app\models;

use app\models\Recipe;
use app\models\SavedRecipe;
use Yii;
use yii\base\Exception;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $email
 * @property string $password
 * @property string $username
 * @property integer $status
 * @property integer $is_moderator
 * @property string $registration_date
 * @property string $last_active_date
 * @property integer $avatar_status
 * @property string $about_me
 * @property string $birthday_date
 *
 * @property SavedRecipe[] $savedRecipes
 * @property Recipe[] $recipes
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const USER_ROLE = 1;
    const ADMIN_ROLE = 2;
    const STATUS_DISABLED = 1;
    const STATUS_ACTIVE = 2;
    const AVATAR_PRESENT = 1;
    const AVATAR_ABSENT = 2;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password', 'username', 'status', 'is_moderator', 'registration_date', 'last_active_date', 'avatar_status'], 'required'],
            [['status', 'is_moderator', 'avatar_status'], 'integer'],
            [['registration_date', 'last_active_date', 'birthday_date'], 'safe'],
            [['email', 'username'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 150],
            [['about_me'], 'string', 'max' => 300],
        ];
    }

    public static function checkAuthorByRecId($id)
    {
        $recipe = Recipe::findOne((int)$id);
        if (\Yii::$app->user->isGuest || \Yii::$app->user->identity->id != $recipe->author)
            die();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            //'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'username' => 'Ім\'я',
            'status' => 'Status',
            'is_moderator' => 'Is Moderator',
            'registration_date' => 'Дата реєстрації',
            'last_active_date' => 'Last Active Date',
            'avatar_status' => 'Avatar Status',
            'about_me' => 'Про мене',
            'birthday_date' => 'День Народження',
        ];
    }

    public function signIn()
    {
        $identity = static::findOne(['email' => $this->email]);
        $hash = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        if ($identity && Yii::$app->getSecurity()->validatePassword($identity->password, $hash)) {
            Yii::$app->user->login($identity);
            return true;
        } else {
            Yii::$app->session->setFlash('login_error', 'Щось такого юзера нема... або невірний пароль!');
            return false;
        }


    }

    public function signUp()
    {
        if (static::findOne(['email' => $this->email])) {
            throw new Exception('user with this email already exists!');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->is_moderator = self::USER_ROLE;
        $this->registration_date = date('Y-m-d', time());
        $this->last_active_date = date('Y-m-d', time());
        $this->avatar_status = self::AVATAR_ABSENT;
        $this->save();
        Yii::$app->user->login($this);
        return true;
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSavedRecipes()
    {
        return $this->hasMany(SavedRecipe::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipes()
    {
        return $this->hasMany(Recipe::className(), ['id' => 'recipe_id'])->viaTable('saved_recipe', ['user_id' => 'id']);
    }
}
