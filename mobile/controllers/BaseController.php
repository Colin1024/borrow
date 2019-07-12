<?php
namespace mobile\controllers;

use backend\models\AdminSystem;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;

class BaseController extends Controller
{
    // 初始化
    public function init()
    {
        // 网站开关状态
        $website_status = AdminSystem::findOne(['key'=>'website_status']);
        if($website_status['content'] == 0){
            echo '<h3>关站中~</h3>';exit;
        }
        // 网站标题
        $title = AdminSystem::findOne(['key'=>'title']);
        $view = $this->getView();
        $view->params['title'] = $title['content'];
    }


    // 统一获取post数据
    public function post($name = null, $defaultValue = null){
        $post = Yii::$app->request->post($name, $defaultValue);
        if(is_array($post)){
            $post = array_map('trim',$post);
            $post = array_map('htmlspecialchars',$post);
        }else{
            $post = htmlspecialchars(trim($post));
        }
        return $post;
    }

    // 统一获取get数据
    public function get($name = null, $defaultValue = null)
    {
        $get = Yii::$app->request->get($name, $defaultValue);
        if(is_array($get)){
            $get = array_map('trim',$get);
            $get = array_map('htmlspecialchars',$get);
        }else{
            $get = htmlspecialchars(trim($get));
        }
        return $get;
    }

    // 调试函数
    public function dd(){
        $param = func_get_args();
        foreach ($param as $p)  {
            VarDumper::dump($p, 10, true);
        }
        exit(1);
    }


}
