<?php
namespace mobile\controllers;

use mobile\models\Member;
use Yii;
class InfoController extends BaseController
{
    // 我的信息
    public function actionIndex()
    {
        if(!Yii::$app->session['is_login']){
            return $this->redirect(['member/login']);
        }
        $member = Member::findOne(Yii::$app->session['id']);
        return $this->render('index',['member'=>$member]);
    }
}