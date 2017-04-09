<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m170409_032915_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->notNull()->unique()->comment('用户名'),
            'password_hash' => $this->string()->notNull()->comment('密码'),
            'tel' => $this->char(11)->notNull()->comment('电话'),
            'email' => $this->string(30)->notNull()->unique()->comment('邮箱'),
            'status' => $this->smallInteger(4)->notNull()->comment('状态'),
            'token' => $this->string(32)->notNull()->comment('自动登录令牌'),
            'add_time' => $this->integer(11)->notNull()->defaultValue(0)->comment('注册时间'),
            'last_login_time' => $this->integer(11)->notNull()->defaultValue(0)->comment('最后登录时间'),
            'last_login_ip' => $this->string(15)->comment('最后登录ip'),
            'auth_key' => $this->string(32)->comment('认证'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
