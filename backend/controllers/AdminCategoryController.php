<?php

namespace backend\controllers;

use Yii;
use yii\data\Pagination;
use backend\models\AdminCategory;
use yii\web\NotFoundHttpException;

/**
 * AdminCategoryController implements the CRUD actions for AdminCategory model.
 */
class AdminCategoryController extends BaseController
{
	public $layout = "lte_main";

    // 列表页
    public function actionIndex()
    {
        $query = AdminCategory::find();
        $search = $this->get('query');
        $query = AdminCategory::condition($query,$search);
        $models = $query
            ->orderBy(['level'=>SORT_ASC])
            ->asArray()
            ->all();
        $prefixModels = AdminCategory::addPrefix($models);
        $category = AdminCategory::categoryTree();
        $categoryTree = json_encode(AdminCategory::getJsTree($models));
        return $this->render('index', [
            'models'=>$prefixModels,
            'category'=>$category,
            'categoryTree'=>$categoryTree,
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
            $category = AdminCategory::findOne(['pid'=>$id]);
            if($category){
                return json_encode(['status'=>100,'msg'=>'该分类下有子分类不允许删除']);
            }
            $res = AdminCategory::deleteAll(['id'=>$id]);
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
            foreach($ids as $id){
                $category = AdminCategory::findOne(['pid'=>$id]);
                if($category){
                    return json_encode(['status'=>100,'msg'=>'选中的分类下有子分类不允许删除']);
                }
            }
            $res = AdminCategory::deleteAll(['in','id',$ids]);
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
        if(Yii::$app->request->isAjax){
            $model = new AdminCategory();
            $get = $this->get();
            $res = $model->create($get);
            if($res){
                return json_encode(['status'=>200,'msg'=>'添加成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'添加失败']);
            }
        }
    }

    // 更新
    public function actionUpdate()
    {
        if(Yii::$app->request->isAjax){
            $get = $this->get();
            $model = new AdminCategory();
            $res = $model->renewal($get);
            if($res){
                return json_encode(['status'=>200,'msg'=>'更新成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'更新失败']);
            }
        }
        $this->layout = 'lte_main2';
        $id = $this->get('id');
        if(!$id){
            throw new NotFoundHttpException('参数不合法');
        }
        $model = AdminCategory::findOne($id);
        return $this->render('update', [
            'model'=>$model,
        ]);
    }

}
