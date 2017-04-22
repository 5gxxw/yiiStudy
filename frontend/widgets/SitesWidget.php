<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/14
 * Time: 16:43
 */

namespace frontend\widgets;


use yii\base\Widget;

class SitesWidget extends Widget
{
    public $sites;

    public function run(){
        if($this->sites != null){
            foreach($this->sites as $site){
                $html = '<p><input type="radio" value="'.$site->id.'" name="address_id" />'.$site->name.' '.$site->tel.' '.$site->province.' '.$site->city.' '.$site->area.' '.$site->particular.' </p>';
            }
        }else{
            $html = '<p>没有收货地址,<a href="/delivery/add">点击去填写收货地址</a></p>';
        }

        return $html;
    }
}