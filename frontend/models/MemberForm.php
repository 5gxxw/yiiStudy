<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/9
 * Time: 15:57
 */

namespace frontend\models;

use yii\base\Model;

class MemberForm extends Model
{
    public $username;
    public $password;
    public $rememberMe;
    public $captcha;

    public function rules(){
        return [
            [['username','password','captcha'],'required'],
            [['username'], 'string', 'max' => 20,'min' => 3,],
            [['password'], 'string', 'max' => 255],
            [['captcha'],'captcha'],
            [['rememberMe'],'safe'],
        ];
    }

    public function attributeLabels(){
        return [
            'username' => '用户名：',
            'password' => '密码：',
            'captcha' => '验证码：',
        ];
    }

    /**
     * 用户登录
     */
    public function memberLogin(){
        //>>1.根据用户名查找用户信息
        $member = Member::findOne(['username'=>$this->username]);
        if ($member){
            //>>1.验证密码
            if(\Yii::$app->security->validatePassword($this->password,$member->password_hash)){
                //>>验证成功,修改最后登录时间和最后登录ip
                $member->last_login_time = time();
                $member->last_login_ip = ip2long(\Yii::$app->request->userIP);
                $member->save();
                //>>登录
                \Yii::$app->user->login($member,$this->rememberMe ? 3600*24*7 : 0);
                return true;
            }else{
                $this->addError('password','密码填写错误');
                return false;
            }
        }else{
            $this->addError('username','用户名不存在');
            return false;
        }

    }
}