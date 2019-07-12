<?php

namespace backend\controllers;

use Yii;
use backend\models\AdminSystem;

/**
 * AdminSystemController implements the CRUD actions for AdminSystem model.
 */
class AdminSystemController extends BaseController
{
	public $layout = "lte_main";
    // 列表页
    public function actionIndex()
    {
        $model = AdminSystem::find()->asArray()->all();
        $data = [];
        foreach ($model as $val){
            $data[$val['key']] = $val;
        }
        return $this->render('index',[
            'data'=>$data,
        ]);
    }

    // 更新
    public function actionUpdate(){
        if(Yii::$app->request->isAjax){
            $post = $this->post();
            $model = new AdminSystem();
            $res = $model->edit($post);
            if($res){
                return json_encode(['status'=>200,'msg'=>'修改成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'修改失败']);
            }
        }
    }

}
