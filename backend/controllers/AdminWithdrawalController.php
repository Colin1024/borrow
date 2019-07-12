<?php

namespace backend\controllers;

use backend\models\AdminMoneyLog;
use Yii;
use yii\data\Pagination;
use backend\models\AdminWithdrawal;
use yii\web\NotFoundHttpException;

/**
 * AdminWithdrawalController implements the CRUD actions for AdminWithdrawal model.
 */
class AdminWithdrawalController extends BaseController
{
	public $layout = "lte_main";
    // 列表页
    public function actionIndex()
    {
        $query = AdminWithdrawal::find()->joinWith(['member']);
        $search = $this->get('query');
        $query = AdminWithdrawal::condition($query,$search);
        $pagination = new Pagination([
                'totalCount' =>$query->count(),
                'pageSize' => '10',
                'pageParam'=>'page',
                'pageSizeParam'=>'per-page']
        );
        $models = $query
            ->orderBy(['admin_withdrawal.create_time'=>SORT_DESC])
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
            $res = AdminWithdrawal::deleteAll(['id'=>$id]);
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
            $res = AdminWithdrawal::deleteAll(['in','id',$ids]);
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
            $model = new AdminWithdrawal();
            $res = $model->audit($id,$status);
            if($res){
                return json_encode(['status'=>200,'msg'=>'审核成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'审核失败']);
            }
        }
    }
    
    // 导出
    public function actionExport()
    {
        $query = AdminWithdrawal::find()->joinWith(['member']);
        $search = $this->get('query');
        $query = AdminWithdrawal::condition($query,$search);
        $data = $query ->orderBy(['admin_withdrawal.create_time'=>SORT_DESC])
            ->asArray()
            ->all();
        $header = ['ID','用户名','金额','状态','创建时间'];
        $list = [implode(',',$header)];
        $status=['1'=>'待审核','2'=>'审核通过','3'=>'审核未通过','4'=>'提现成功'];
        if($data){
            foreach ($data as $val){
                $item = [
                    $val['id'],
                    $val['member']['name']." | ". $val['member']['tel'],
                    $val['money'],
                    $status[$val['status']],
                    date('Y-m-d H:i:s',$val['create_time']),
                ];
                $list[] = implode(',',$item);
            }
        }
        return $this->csvExport($list);
    }

}
