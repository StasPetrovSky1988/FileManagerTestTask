<?php
namespace app\models\ActiveRecords;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $email
 * @property string $access_level
 * @property string $verification_token
 */

class User extends ActiveRecord  implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 5;
    const STATUS_ACTIVE = 10;

    const ACCESS_ADMIN = 7;
    const ACCESS_USER = 0;

    public static function tableName()
    {
        return '{{%users}}';
    }

    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE], //TODO пользователя требуется подтвердить по почте
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],

            [['username','email','password_hash','status'], 'required'],
            [['status'], 'integer', 'min'=>0, 'max'=>10],
            [['access_level'], 'integer', 'min'=>0, 'max'=>7],
            [['password_hash'], 'string', 'min'=>4, 'max'=>256],
            [['username'], 'string', 'min'=>3, 'max'=>55],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['status' ,'created_at' ,'updated_at' ,'access_level'], 'integer'],
        ];
    }

    public function isAdmin()
    {
        return $this->access_level >= self::ACCESS_ADMIN;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::find()->where(['id' => $id])->andWhere(['>=','status', self::STATUS_ACTIVE])->one();
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('Identify by access token not supported');
    }

    public static function findByUsername($username)
    {
        throw new NotSupportedException('findByUsername not supported');
    }

    public static function findByEmail($email)
    {
        return self::find()->where(['email' => $email])->andWhere(['>=','status', self::STATUS_ACTIVE])->one();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
}
