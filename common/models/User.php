<?php

namespace common\models;


use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property string $image
 * @property string $role
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address'], 'string'],
            [['username', 'password', 'phone', 'email', 'image', 'role'], 'string', 'max' => 255],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'phone' => 'Phone',
            'email' => 'Email',
            'address' => 'Address',
            'image' => 'Image',
            'role' => 'Role',
        ];
    }
    public static function findByUsername($username)
    {
     return static::findOne(['username' => $username]);
    }
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    public function validatePassword($password)
    {
     return \Yii::$app->security->validatePassword($password, $this->password);
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
}
