<?php

namespace mobile\controllers;


use backend\models\AdminPage;

class PageController extends BaseController
{
    //首页
    public function actionIndex(){
      $id = (int)$this->get('id');
      if(!$id){
          throw new \Exception('参数错误');
      }
      $page = AdminPage::findOne($id);
      if(!$page){
          throw new \Exception('文章不存在');
      }
      return $this->render('index',['page'=>$page]);
    }
}