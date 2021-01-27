<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use Yii;

/**
 * UserIdentity class for "user" table.
 * This is a base user class that is implementing IdentityInterface.
 * User model should extend from this model, and other user related models should
 * extend from User model.
 *
 * @property integer $id
 * @property string  $name
 * @property string  $password
 * @property string  $password_reset_token
 * @property string  $email
 * @property string  $consumer
 * @property string  $access_given
 * @property string  $account_activation_token
 * @property integer $created
 * @property integer $updated
 */
class UserIdentity extends ActiveRecord implements IdentityInterface
{
    public $auth_key;
    /**
     * Declares the name of the database table associated with this AR class.
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => '\yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created', 'updated'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated'],
                ],
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }


//------------------------------------------------------------------------------------------------//
// IDENTITY INTERFACE IMPLEMENTATION
//------------------------------------------------------------------------------------------------//

    /**
     * Finds an identity by the given ID.
     *
     * @param  int|string $id The user id.
     * @return IdentityInterface|static
     */
    public static function findIdentity($id)
    {
        return static::findOne(['ID' => $id]);
    }

    /**
     * Finds an identity by the given access token.
     *
     * @param  mixed $token
     * @param  null  $type
     * @return void|IdentityInterface
     * 
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $cookies = Yii::$app->request->cookies;
        $accessTokens = isset($cookies['_i_aksdja']) && isset($cookies['_siamsdilajs']) ? true : false;
        if($accessTokens) $authToken = $cookies['_siamsdilajs']->value === $token ? true : false;
            if(!$authToken) return $authToken;
                return User::findOne(['ID' => $cookies['_i_aksdja']->value]);
        //return User::findOne(['auth_key' => $token]);
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     *
     * @return int|mixed|string
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Returns a key that can be used to check the validity of a given
     * identity ID. The key should be unique for each individual user, and
     * should be persistent so that it can be used to check the validity of
     * the user identity. The space of such keys should be big enough to defeat
     * potential identity attacks.
     *
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     * 
     * @param  string  $auth_key The given auth key.
     * @return boolean          Whether the given auth key is valid.
     */
    public function validateAuthKey($auth_key)
    {
        return $this->getAuthKey() === $auth_key;
    }

//------------------------------------------------------------------------------------------------//
// IMPORTANT IDENTITY HELPERS
//------------------------------------------------------------------------------------------------//

    /**
     * Generates "remember me" authentication key. 
     */
    public function generateAuthKey()
    {
        $tokenExpiration = 60 * 24 * 365;
        $this->auth_key = Yii::$app->security->generateRandomString();
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => '_siamsdilajs',
            'value' => $this->auth_key,
            'expire' => $tokenExpiration + strtotime("now")
        ]));
    }

    /**
     * @param  string $id
     * Generates "remember me" authentication key. 
     */
    public function setCookie($id)
    {
        $cookies = Yii::$app->response->cookies;
        $hashName = Yii::$app->security->generatePasswordHash($this->name);
        $cookies->add(new \yii\web\Cookie([
            'name' => '_i_aksdja',
            'value' => $id,
            'expire' => strtotime( '+30 Days')
        ]));
        $cookies->add(new \yii\web\Cookie([
            'name' => '_aksjdias',
            'value' => $hashName,
            'expire' => strtotime( '+30 Days')
        ]));
    //     AccessToken::generateAuthKey($this);
    }

    /**
     * Validates password.
     *
     * @param  string $password
     * @return bool
     * 
     * @throws \yii\base\InvalidConfigException
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model.
     *
     * @param  string $password
     * 
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

}
