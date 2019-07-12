<?php

namespace backend\controllers;

use backend\models\AdminCategory;
use common\models\UploadForm;
use Yii;
use yii\data\Pagination;
use backend\models\AdminProduct;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * AdminProductController implements the CRUD actions for AdminProduct model.
 */
class AdminProductController extends BaseController
{
	public $layout = "lte_main";
	public $enableCsrfValidation = false;
    // 列表页
    public function actionIndex()
    {
        $query = AdminProduct::find()->joinWith('category');
        $search = $this->get('query');
        $query = AdminProduct::condition($query,$search);
        $pagination = new Pagination([
                'totalCount' =>$query->count(),
                'pageSize' => '10',
                'pageParam'=>'page',
                'pageSizeParam'=>'per-page']
        );
        $models = $query
            ->orderBy(['admin_product.sort'=>SORT_DESC,'admin_product.create_time'=>SORT_DESC])
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
    
    // 查看
    public function actionView()
    {
        $id = (int)$this->get('id');
        if(!$id){
            throw new NotFoundHttpException('参数不合法');
        }
        $model = AdminProduct::findOne($id);
        $category = AdminCategory::categoryTree();
        unset($category[0]);
        return $this->render('view',[
            'model'=>$model,
            'category'=>$category
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
            $res = AdminProduct::deleteAll(['id'=>$id]);
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
            $res = AdminProduct::deleteAll(['in','id',$ids]);
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
            $model = new AdminProduct();
            $res = $model->create($post);
            if($res){
                return json_encode(['status'=>200,'msg'=>'添加成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'添加失败']);
            }
        }
        $category = AdminCategory::categoryTree();
        unset($category[0]);
        return $this->render('create',[
            'category'=>$category
        ]);
    }

    // 图片上传
    public function actionUpload()
    {
        if(Yii::$app->request->isAjax){
            $model = new UploadForm();
            // 上传文件对象
            if(isset($_FILES['file'])){
                $model->imageFile = UploadedFile::getInstanceByName('file');
            }else{
                $model->imageFile = UploadedFile::getInstanceByName('file_bg');
            }
            $res = $model->upload();
            return $res;
        }
    }

    // 更新
    public function actionUpdate(){
        if(Yii::$app->request->isPost){
            $post = $this->post();
            $model = new AdminProduct();
            $res = $model->edit($post);
            if($res){
                return json_encode(['status'=>200,'msg'=>'修改成功']);
            }else{
                return json_encode(['status'=>200,'msg'=>'修改失败']);
            }
        }
        $id = (int)$this->get('id');
        if(!$id){
            throw new NotFoundHttpException('参数不合法');
        }
        $model = AdminProduct::findOne($id);
        $category = AdminCategory::categoryTree();
        unset($category[0]);
        return $this->render('update',[
            'model'=>$model,
            'category'=>$category
        ]);
    }
}
