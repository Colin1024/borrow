<?php

namespace backend\controllers;

use Yii;
use yii\data\Pagination;
use backend\models\AdminApply;
use yii\web\NotFoundHttpException;

/**
 * AdminApplyController implements the CRUD actions for AdminApply model.
 */
class AdminApplyController extends BaseController
{
	public $layout = "lte_main";
    // 列表页
    public function actionIndex()
    {
        $query = AdminApply::find()->joinWith(['member','product']);
        $search = $this->get('query');
        $query = AdminApply::condition($query,$search);
        $pagination = new Pagination([
                'totalCount' =>$query->count(),
                'pageSize' => '10',
                'pageParam'=>'page',
                'pageSizeParam'=>'per-page']
        );
        $models = $query
            ->orderBy(['admin_apply.create_time'=>SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();
        return $this->render('index', [
            'models'=>$models,
            'pages'=>$pagination,
            'query'=>$search,
        ]);
    }

    // 删除
    public function actionDelete()
    {
        if(Yii::$app->request->isAjax){
            $id = (int)$this->get('id');
            if(!$id){
                throw new NotFoundHttpException('参数不合法');
            }
            $res = AdminApply::deleteAll(['id'=>$id]);
            if($res){
                return json_encode(['status'=>200,'msg'=>'删除成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'删除失败']);
            }
        }
    }

    // 批量删除
    public function actionBatchDel()
    {
        if(Yii::$app->request->isAjax){
            $ids = $this->post('ids');
            if(!$ids){
                throw new NotFoundHttpException('参数不合法');
            }
            $ids = explode(',',rtrim($ids,','));
            $res = AdminApply::deleteAll(['in','id',$ids]);
            if($res){
                return json_encode(['status'=>200,'msg'=>'删除成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'删除失败']);
            }
        }
    }
    
    // 审核
    public function actionAudit()
    {
        if(Yii::$app->request->isAjax){
            $id = (int)$this->get('id');
            $status = (int)$this->get('status');
            if(!$id){
                throw new NotFoundHttpException('参数不合法');
            }
            AdminApply::updateAll(['status'=>$status],['id'=>$id]);
            return json_encode(['status'=>200,'msg'=>'审核成功']);
        }
    }

    // 签名查看
    public function actionSignatureView()
    {
        if(Yii::$app->request->isAjax){
            $id = (int)$this->post('id');
            if(!$id){
                throw new NotFoundHttpException('参数不合法');
            }
            $signature = AdminApply::findOne($id)->signature;
            return json_encode(['status'=>200,'signature'=>$signature]);
        }
    }

    // 签名查看
    public function actionProtocolView()
    {
        if(Yii::$app->request->isAjax){
            $id = (int)$this->post('id');
            if(!$id){
                throw new NotFoundHttpException('参数不合法');
            }
            $protocol = AdminApply::findOne($id)->protocol;
            return json_encode(['status'=>200,'protocol'=>$protocol]);
        }
    }

    // 查看其他信息
    public function actionOtherInfo()
    {
       if(Yii::$app->request->isAjax){
           $id = (int)$this->get('id');
           if(!$id){
               throw new NotFoundHttpException('参数不合法');
           }
           $other_info = AdminApply::findOne($id)->other_info_val;
           return json_encode(['status'=>200,'other_info'=>json_decode($other_info,JSON_UNESCAPED_UNICODE)]);
       }
    }


    // 导出
    public function actionExport()
    {
        $query = AdminApply::find()->joinWith(['member','product']);
        $search = $this->get('query');
        $query = AdminApply::condition($query,$search);
        $data = $query ->orderBy(['admin_apply.create_time'=>SORT_DESC])
            ->asArray()
            ->all();
        $header = ['ID','用户名','产品','金额','用途','期限','签名','借条','借款时间','还款时间','状态','创建时间'];
        $list = [implode(',',$header)];
        $status=['1'=>'待审核','2'=>'审批通过','3'=>'审批未通过','4'=>'已放款','5'=>'已还款'];
        if($data){
            foreach ($data as $val){
                $item = [
                    $val['id'],
                    $val['member']['name']." | ". $val['member']['tel'],
                    $val['product']['name'],
                    $val['money'],
                    $val['use'],
                    $val['time_limit'],
                    $val['signature'] ? '存在' : '未生成',
                    $val['protocol'] ? '存在' : '未生成',
                    date('Y-m-d H:i:s',$val['b_time']),
                    date('Y-m-d H:i:s',$val['e_time']),
                    $status[$val['status']],
                    date('Y-m-d H:i:s',$val['create_time']),
                ];
                $list[] = implode(',',$item);
            }
        }
        return $this->csvExport($list);
    }

}
