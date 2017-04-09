<?php
/**
 * 自定义过滤器
 */
namespace backend\filter;

class MyAccessFilter extends \yii\base\ActionFilter
{
    /**
     * 执行操作之前验证
     * $action 控制器执行的操作对象
     * $action->uniqueId 就是当前操作,格式就是路由
     */
    public function beforeAction($action)
    {
        //>>判断当前用户是否有该操作的权限 can()
        if(!\Yii::$app->user->can($action->uniqueId)){
            //>>判断当前用户是否已经登录,没有登录跳转到登录页面
            if(\Yii::$app->user->isGuest){
               return $action->controller->redirect(\Yii::$app->user->loginUrl);
            }
            //>>提示403
            throw new \yii\web\HttpException(403,'对不起,您没有该操作权限');

            return false;
        }

        return parent::beforeAction($action);
    }

}