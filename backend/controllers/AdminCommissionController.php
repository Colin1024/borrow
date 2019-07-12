<?php

namespace backend\controllers;

use backend\models\AdminSystem;
use common\models\UploadForm;
use Yii;
use yii\data\Pagination;
use backend\models\AdminCommission;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * AdminCommissionController implements the CRUD actions for AdminCommission model.
 */
class AdminCommissionController extends BaseController
{
    public $layout = "lte_main";

    // 列表页
    public function actionIndex()
    {
        $query = AdminCommission::find();
        $search = $this->post('query');
        $query = AdminCommission::condition($query,$search);
        $pagination = new Pagination([
                'totalCount' =>$query->count(),
                'pageSize' => '10',
                'pageParam'=>'page',
                'pageSizeParam'=>'per-page']
        );
        $models = $query
            ->orderBy(['create_time'=>SORT_DESC])
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
            $res = AdminCommission::deleteAll(['id'=>$id]);
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
            $res = AdminCommission::deleteAll(['in','id',$ids]);
            if($res){
                return json_encode(['status'=>200,'msg'=>'删除成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'删除失败']);
            }
        }
    }

    // 模板下载
    public function actionDownload()
    {
        $file_dir = dirname(__DIR__).'/web/dist/csv/';
       // $file_dir = Yii::getAlias('@backend/web/dist/csv/');
       // $file_dir = Yii::getAlias('@backend').'/web/dist/csv/';
        $file_name = 'commission_tpl.csv';
        return self::fileDownload($file_dir,$file_name);
    }

    // 导出
    public function actionExport()
    {
        $query = AdminCommission::find();
        $search = $this->get('query');
        $query = AdminCommission::condition($query,$search);
        $data = $query ->orderBy(['create_time'=>SORT_DESC])
            ->asArray()
            ->all();
        $header = ['ID','手机号','产品','已放款金额','已还款金额','返佣金额','匹配状态','分配状态','创建时间'];
        $list = [implode(',',$header)];
        if($data){
            foreach ($data as $val){
                $item = [
                    $val['id'],
                    $val['tel'],
                    $val['product'],
                    $val['loan'],
                    $val['repayment'],
                    $val['commission'],
                    $val['is_match'] == 2 ? '已匹配' : '未匹配',
                    $val['is_allot'] == 2 ? '已分配' : '未分配',
                    date('Y-m-d H:i:s',$val['create_time']),
                ];
                $list[] = implode(',',$item);
            }
        }
        return $this->csvExport($list);
    }

    // 导入
    public function actionImport()
    {
        if(Yii::$app->request->isAjax){
            $ext = pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
            if($ext != 'csv'){
                return json_encode(['status'=>100,'msg'=>'请上传csv文件']);
            }
            $model = new UploadForm();
            $model->csvFile = UploadedFile::getInstanceByName('file');
            $res = json_decode($model->upload('csv'),true);
            if($res['status'] == 200){
                $handle = fopen($res['path'],'r');
                $result = self::inputCsv($handle);
                $len = count($result);
                if($len == 1){  // 只有表头
                    return json_encode(['status'=>100,'msg'=>'没有任何数据']);
                }
                $data = [];
                for($i=1;$i<$len;$i++){
                    $data[$i-1]['tel'] = $result[$i][0];
                    $data[$i-1]['product'] = iconv('gb2312','utf-8',$result[$i][1]);
                    $data[$i-1]['loan'] = $result[$i][2];
                    $data[$i-1]['repayment'] = $result[$i][3];
                    $data[$i-1]['commission'] = $result[$i][4];
                }
                $model = new AdminCommission();
                $res = $model->import($data);
                if($res){
                    return json_encode(['status'=>200,'msg'=>'导入成功']);
                }else{
                    return json_encode(['status'=>100,'msg'=>'导入失败']);
                }
           }
        }
    }

    // 分配佣金
    public function actionAllot()
    {
        if(Yii::$app->request->isAjax){
            // 分销状态
            $commission_status = AdminSystem::findOne(['key'=>'commission_status'])->content;
            if($commission_status == 0){
                return json_encode(['status'=>100,'msg'=>'分销功能未开启']);
            }
            $id = $this->get('id');
            if(!$id){
                throw new NotFoundHttpException('参数不合法');
            }
            $model = new AdminCommission();
            $res = $model->allot($id);
            if($res){
                return json_encode(['status'=>200,'msg'=>'分配成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'分配失败']);
            }
        }
    }

    // 一键分配
    public function actionAllotAll()
    {
        // 分销状态
        $commission_status = AdminSystem::findOne(['key'=>'commission_status'])->content;
        if($commission_status == 0){
            return json_encode(['status'=>100,'msg'=>'分销功能未开启']);
        }
        $models = AdminCommission::findAll(['is_match'=>2,'is_allot'=>1]);
        if(!$models){
            return json_encode(['status'=>100,'msg'=>'没有待分配的记录']);
        }
        $model = new AdminCommission();
        $res = [];
        foreach ($models as $val){
           $res[] = $model->allot($val->id);
        }
        // 结果
        $result = true;
        foreach ($res as $val){
            if(!$val){
                $result = false;
                break;
            }
        }
        if($result){
            return json_encode(['status'=>200,'msg'=>'分配成功']);
        }else{
            return json_encode(['status'=>200,'msg'=>'分配失败']);
        }
    }

}
