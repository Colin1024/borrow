<?php

namespace backend\controllers;

use Yii;
use yii\data\Pagination;
use backend\models\AdminMember;
use yii\web\NotFoundHttpException;

/**
 * AdminMemberController implements the CRUD actions for AdminMember model.
 */
class AdminMemberController extends BaseController
{
	public $layout = "lte_main";
    // 列表页
    public function actionIndex()
    {
        $query = AdminMember::find()->joinWith('member');
        $search = $this->get('query');
        $query = AdminMember::condition($query,$search);
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
            $res = AdminMember::deleteAll(['id'=>$id]);
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
            $res = AdminMember::deleteAll(['in','id',$ids]);
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
            $member = AdminMember::findOne(['tel'=>$post['tel']]);
            if($member){
                return json_encode(['status'=>100,'msg'=>'添加失败,该手机号已存在']);
            }
            $model = new AdminMember();
            $res = $model->create($post);
            if($res){
                return json_encode(['status'=>200,'msg'=>'添加成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'添加失败']);
            }
        }
        return $this->render('create');
    }

    // 更新
    public function actionUpdate(){
        if(Yii::$app->request->isPost){
            $post = $this->post();
            $model = new AdminMember();
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
        $model = AdminMember::findOne($id);
        return $this->render('update',[
            'model'=>$model,
        ]);
    }

    // 导出
    public function actionExport()
    {
        $query = AdminMember::find()->joinWith(['member']);
        $search = $this->get('query');
        $query = AdminMember::condition($query,$search);
        $data = $query ->orderBy(['admin_member.create_time'=>SORT_DESC])
            ->asArray()
            ->all();
        $header = ['ID','推荐人','用户姓名','手机号','身份证号','银行卡号','绑定手机号','身份认证','运营商认证','创建时间'];
        $list = [implode(',',$header)];
        if($data){
            foreach ($data as $val){
                $item = [
                    $val['id'],
                    $val['member'] ? $val['member']['name']." | ". $val['member']['tel'] : '',
                    $val['name'],
                    $val['tel'],
                    "\t".$val['id_card'],
                    "\t".$val['bank_card'],
                    $val['bank_tel'],
                    $val['is_auth'] == 1 ? '未认证' : '已认证',
                    $val['tel_auth'] == 1 ? '未认证' : '已认证',
                    date('Y-m-d H:i:s',$val['create_time']),
                ];
                $list[] = implode(',',$item);
            }
        }
        return $this->csvExport($list);
    }

}
