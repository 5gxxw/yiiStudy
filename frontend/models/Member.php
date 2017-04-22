<?php

namespace frontend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $tel
 * @property string $email
 * @property integer $status
 * @property string $token
 * @property integer $add_time
 * @property integer $last_login_time
 * @property string $last_login_ip
 * @property string $auth_key
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $repassword;

    public $captcha;

    public $checked;

    public $sms;   //短信验证码

    public $password;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'tel', 'email','checked'], 'required'],
            [['status', 'add_time', 'last_login_time'], 'integer'],
            [['username'], 'string', 'max' => 20,'min' => 3,],
            [['password'], 'string', 'max' => 255],
            [['tel'], 'string', 'length' => 11],
            [['email'], 'string', 'max' => 30],
            [['token', 'auth_key'], 'string', 'max' => 32],
            [['last_login_ip'], 'string', 'max' => 15],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['repassword'],'compare','compareAttribute' => 'password','message'=>'两次密码不一致'],
            [['captcha'],'captcha'],    //用户输入的验证码
            [['sms'],'validateSms'], //短信验证码
            //[['checked'],'compare','compareAttribute' => 1,'message'=>'请先阅读用户注册协议'],
            [['tel'],'match','pattern'=>'/^[1][34578]\d{9}$/','message'=>'请填写正确的手机号码格式']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名：',
            'password' => '密码：',
            'tel' => '手机号码：',
            'email' => '邮箱：',
            'status' => '状态',
            'token' => '自动登录令牌',
            'add_time' => '注册时间',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
            'auth_key' => '认证',
            'repassword' => '确认密码：',
            'captcha' => '验证码：',
            'sms' => '验证码',
        ];
    }

    /**
     * 自定义验证短信验证码
     */
    public function validateSms(){
        //>>1.获取session中的验证码和手机号码
        //>>2.将传过来的验证码跟session中的做对比
        if ($this->sms != Yii::$app->session->get('tel_'.$this->tel)){
            $this->addError('sms','验证码输入错误');
        }
    }


    /**
     * 保存用户注册信息
     */
    public function add(){
        //>>1.将密码加密
        $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        //>>生成随机认证标识
        $this->auth_key = Yii::$app->security->generateRandomString();
        //>>2.注册时间,最后登录时间,最后登录ip
        $this->add_time = time();
        $this->last_login_time = time();
        $this->last_login_ip = ip2long(Yii::$app->request->userIP);
        //>>3.保存
        if ($this->save(false)){
            //>>自动登录
            Yii::$app->user->login($this);
            return true;
        }
    }



    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        //>>根据id查询出身份信息,返回
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key == $authKey;
    }
}
