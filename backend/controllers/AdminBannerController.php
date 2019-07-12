<?php

namespace backend\controllers;

use common\models\UploadForm;
use Yii;
use yii\data\Pagination;
use backend\models\AdminBanner;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * AdminBannerController implements the CRUD actions for AdminBanner model.
 */
class AdminBannerController extends BaseController
{
    public $layout = "lte_main";

    // 列表页
    public function actionIndex()
    {
        $query = AdminBanner::find();
        $search = $this->get('query');
         $query = AdminBanner::condition($query,$search);
        $pagination = new Pagination([
                'totalCount' =>$query->count(),
                'pageSize' => '10',
                'pageParam'=>'page',
                'pageSizeParam'=>'per-page']
        );
        $models = $query
            ->orderBy(['sort'=>SORT_DESC,'create_time'=>SORT_DESC])
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

    public function actionView(){
        $id = $this->get('id');
        if(!$id){
           throw new \Exception('参数错误');
        }
        $model = AdminBanner::findOne($id);
        return $this->render('view',['model'=>$model]);
    }

    // 删除
    public function actionDelete()
    {
        if(Yii::$app->request->isAjax){
            $id = (int)$this->get('id');
            if(!$id){
                throw new NotFoundHttpException('参数不合法');
            }
            $res = AdminBanner::deleteAll(['id'=>$id]);
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
            $res = AdminBanner::deleteAll(['in','id',$ids]);
            if($res){
                return json_encode(['status'=>200,'msg'=>'删除成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'删除失败']);
            }
        }
    }

    // 添加
    public function actionCreate()
    {
        if(Yii::$app->request->isPost){
            $post = $this->post();
            $model = new AdminBanner();
            $res = $model->create($post);
            if($res){
                return json_encode(['status'=>200,'msg'=>'添加成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'添加失败']);
            }
        }
        return $this->render('create');
    }

    // 图片上传
    public function actionUpload()
    {
        if(Yii::$app->request->isAjax){
            $model = new UploadForm();
            $model->imageFile = UploadedFile::getInstanceByName('file');
            $res = $model->upload();
            return  $res;
        }
    }

    // 更新
    public function actionUpdate(){
        if(Yii::$app->request->isPost){
            $post = $this->post();
            $model = new AdminBanner();
            $res = $model->edit($post);
            if($res){
                return json_encode(['status'=>200,'msg'=>'修改成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'修改失败']);
            }
        }
        $id = (int)$this->get('id');
        if(!$id){
            throw new NotFoundHttpException('参数不合法');
        }
        $model = AdminBanner::findOne($id);
        return $this->render('update',[
            'model'=>$model,
        ]);
    }

}
