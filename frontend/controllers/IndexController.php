<?php
namespace frontend\controllers;

use mobile\models\Member;
use Yii;
use yii\web\Controller;

class IndexController extends Controller
{
    // 天机接口异步回调
    public function actionNotifyurl(){
        $post = Yii::$app->request->post();
        // $aa = json_encode($post);
        // file_put_contents('4.txt',$aa.'---'.date('Y-m-d H:i:s'));
        if($post['state'] == 'report'){
            $detail=$this->detail($post['userId'],$post['outUniqueId']);
            $member = Member::findOne($post['userId']);
            $member->report_detail = $detail;
            $member->save(false);
         //   file_put_contents('5.txt',$detail.'---'.date('Y-m-d H:i:s'));
        }

    }
    public function detail($userId,$outUniqueId){
        include dirname(dirname(__DIR__)).'/extend/tianji/OpenapiClient.php';

        $sample= new \OpenapiClient();
        $sample->setMethod('tianji.api.tianjireport.detail');
        $sample->setField('userId', $userId);
        $sample->setField('outUniqueId',$outUniqueId);
        $sample->setField('reportType','html');
        $ret= $sample->execute();
        return json_encode($ret);
    }

}
