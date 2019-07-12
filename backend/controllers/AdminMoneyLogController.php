<?php

namespace backend\controllers;

use Yii;
use yii\data\Pagination;
use backend\models\AdminMoneyLog;
use yii\web\NotFoundHttpException;

/**
 * AdminMoneyLogController implements the CRUD actions for AdminMoneyLog model.
 */
class AdminMoneyLogController extends BaseController
{
	public $layout = "lte_main";
    // 列表页
    public function actionIndex()
    {
        $query = AdminMoneyLog::find()->joinWith(['member']);
        $search = $this->get('query');
        $query = AdminMoneyLog::condition($query,$search);
        $pagination = new Pagination([
                'totalCount' =>$query->count(),
                'pageSize' => '10',
                'pageParam'=>'page',
                'pageSizeParam'=>'per-page']
        );
        $models = $query
            ->orderBy(['admin_money_log.create_time'=>SORT_DESC])
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
            $res = AdminMoneyLog::deleteAll(['id'=>$id]);
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
            $res = AdminMoneyLog::deleteAll(['in','id',$ids]);
            if($res){
                return json_encode(['status'=>200,'msg'=>'删除成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'删除失败']);
            }
        }
    }
    

    // 导出
    public function actionExport()
    {
        $query = AdminMoneyLog::find()->joinWith(['member']);
        $search = $this->get('query');
        $query = AdminMoneyLog::condition($query,$search);
        $data = $query ->orderBy(['admin_money_log.create_time'=>SORT_DESC])
            ->asArray()
            ->all();
        $header = ['ID','用户名','金额','方式','类型','账户余额','创建时间'];
        $list = [implode(',',$header)];
        if($data){
            foreach ($data as $val){
                $item = [
                    $val['id'],
                    $val['member']['name']." | ". $val['member']['tel'],
                    $val['money'],
                    $val['way']==1 ? '返佣': '提现' ,
                    $val['type']==1 ? '增加': '减少',
                    $val['balance'],
                    date('Y-m-d H:i:s',$val['create_time']),
                ];
                $list[] = implode(',',$item);
            }
        }
        return $this->csvExport($list);
    }

}
