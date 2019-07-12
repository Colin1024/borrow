<?php

namespace mobile\controllers;

use backend\models\AdminSystem;

class SystemController extends BaseController
{
    // 客服
    public function actionIndex(){
        $qq = AdminSystem::findOne(['key'=>'qq'])->content;
        $phone = AdminSystem::findOne(['key'=>'tel'])->content;
        return $this->render('index',['qq'=>$qq,'phone'=>$phone]);
    }

}