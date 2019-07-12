<?php
namespace mobile\controllers;

use backend\models\AdminSystem;
use mobile\models\Apply;
use mobile\models\Member;
use Yii;
use backend\models\AdminCategory;
use backend\models\AdminProduct;
use yii\helpers\Url;

class ProductController extends BaseController
{
    public $limit = 5;
    // 全部产品
    public function actionIndex()
    {
        $cate_id = (int)$this->get('cate_id');
        $cates = AdminCategory::find()->all();
        if($cate_id){
            $products = AdminProduct::find()->where(['cate_id'=>$cate_id])->orderBy(['sort'=>SORT_DESC,'id'=>SORT_DESC])->limit($this->limit)->all();
            /*if(!$products){
                throw new \Exception('参数错误');
            }*/
        }else{
            $products = AdminProduct::find()->limit($this->limit)->all();
        }
        return $this->render('index',['cates'=>$cates,'products'=>$products]);
    }

    // 加载更多
    public function actionMore()
    {
        if(Yii::$app->request->isAjax){
            $cate_id = $this->get('cate_id');
            $offset = $this->get('offset');
            if($cate_id){
                $products = AdminProduct::find()->where(['cate_id'=>$cate_id])->orderBy(['sort'=>SORT_DESC,'id'=>SORT_DESC])
                    ->offset($offset)->limit($this->limit)->asArray()->all();
            }else{
                $products = AdminProduct::find()->orderBy(['sort'=>SORT_DESC,'id'=>SORT_DESC])
                    ->offset($offset)->limit($this->limit)->asArray()->all();
            }
            if(!$products){
                return json_encode(['status'=>100,'msg'=>'没有更多数据']);
            }
            $offset = $offset + $this->limit;
            $content = '';
            foreach($products as $product){
                $content .= '<li>';
                $content .= '<h1>';
                $content .= $product['name'];
                $labels = explode(',',trim($product['label'],','));
                foreach ($labels as $label){
                    $content .=  '<i>'.$label.'</i>';
                }
                $content .= '</h1>';
                $content .= '<div class="index_main_con">';
                $content .= '<div class="imc_left fl"><img src="'.$product['thumb'].'"></div>';
                $content .= '<div class="imc_left fl">';
                $content .= '<h2>'.$product['description'].'</h2>';
                $content .= '<div class="imc_mid_con">';
                $content .= '<div class="imcm_left fl"><p>申请人数</p><h3>'.$product['apply_num'].'</h3></div>';
                $content .= '<div class="imcm_right fl"><p>申请金额</p><h3>'.$product['min_money'].'-'.$product['max_money'].'</h3></div>';
                $content .= '</div>';
                $content .= '</div>';
                $content .= '<div class="imc_right fr"><a href="'.Url::toRoute(['product/detail','id'=>$product['id']]).'">立即申请</a></div>';
                $content .= '<div class="clear"></div>';
                $content .= '</div>';
                $content .= '</li>';
            }
            return json_encode(['status'=>200,'data'=>$content,'offset'=>$offset]);
        }
    }

    // 产品详情
    public function actionDetail()
    {
        $id = (int)$this->get('id');
        if(!$id){
            throw new \Exception('参数错误');
        }
        $product = AdminProduct::findOne($id);
        return $this->render('detail',['product'=>$product]);
    }

    // 申请详情
    public function actionApply()
    {
        if(!Yii::$app->session['is_login']){
            return $this->redirect(['member/login']);
        }
        $time_limit_scale = AdminSystem::findOne(['key'=>'time_limit_scale'])->content;
        $time_limit_scale = explode(',',$time_limit_scale);
        $min_time_limit = $time_limit_scale[0];
        $max_time_limit = $time_limit_scale[1];
        $product_id = (int)$this->get('product_id');
        if(!$product_id){
            throw new \Exception('参数错误');
        }
        $product = AdminProduct::findOne($product_id);
        return $this->render('apply',['product'=>$product,'min_time_limit'=>$min_time_limit,'max_time_limit'=>$max_time_limit]);
    }

    // todo 检查最近一笔订单状态
    public function actionApplyStatus()
    {
        if(!Yii::$app->session['is_login']){
            return $this->redirect(['member/login']);
        }
        $apply = Apply::find()->where(['mem_id'=>Yii::$app->session['id']])->orderBy(['id'=>SORT_DESC])->limit(1)->one();
        if(!$apply || $apply->status == 5){
            return json_encode(['status'=>200]);
        }
        return json_encode(['status'=>100,'msg'=>'你最近的一笔借款未还款完成，请完成后再来']);
    }

    // 保存申请信息
    public function actionSaveApply()
    {
        if(Yii::$app->request->isAjax){
            $post = $this->post();
            $apply = new Apply();
            $res = $apply->checkApplyInfo($post);
            if($res['status'] == 200){
                // 申请信息暂存于session中，申请成功后，统一保存数据库（防止客户修改，生成多个申请）;
                $apply_info = ['money'=>$post['money'],'time_limit'=>$post['time_limit'],'use'=>$post['use'],'product_id'=>$post['product_id']];
                Yii::$app->session['apply_info'] = $apply_info;
                return json_encode(['status'=>200,'msg'=>$res['msg']]);
            }else{
                return json_encode(['status'=>100,'msg'=>$res['msg']]);
            }
        }
    }

    // 身份认证
    public function actionAuth()
    {
        if(!Yii::$app->session['is_login']){
            return $this->redirect(['member/login']);
        }
        $product_id = (int)$this->get('product_id');
        if(!$product_id){
            throw new \Exception('参数错误');
        }
        $member = Member::findOne(Yii::$app->session['id']);
        $product = AdminProduct::findOne($product_id);
        return $this->render('auth',['product'=>$product,'member'=>$member]);
    }
    
    // 保存身份信息
    public function actionSaveAuth()
    {
        if(Yii::$app->request->isAjax){
            $post = $this->post();
            $product_id  = (int)$post['product_id'];
            if(!$product_id){
                return json_encode(['status'=>100,'msg'=>'参数错误']);
            }
            $member = new Member();
            $res = $member->checkAuth($post);
            if($res['status'] == 200){
                  $product = AdminProduct::findOne($product_id);
                /* if($product['auth_need'] == 1){ // 需要身份认证
                     $res = PublicController::bankCard($post['real_name'],$post['id_card'],$post['bank_card'],$post['bank_tel']);
                     if($res['code'] != 0 || $res['result']['res'] !=1){
                         return json_encode(['status'=>100,'msg'=>$res['result']['description']]);
                     }
                 }*/

                $res = $member->saveAuth($post);

                if($res['status'] != 200){
                    return json_encode(['status'=>100,'msg'=>$res['msg']]);
                }

                /*if($product['tel_auth_need'] == 1){ // 需要手机认证(运营商认证)
                    $out_unique_id = time().mt_rand(10000000,99999999);
                    $member = Member::findOne(Yii::$app->session['id']);
                    $member->out_unique_id = $out_unique_id;
                    $member->save(false);
                    $result = PublicController::collectuser(Yii::$app->session['id'],$post['real_nam'],$post['id_card'],$member->tel,$out_unique_id);
                    if ($result['error'] == 200) {
                        return json_encode(['status' => '200', 'url' => $result['tianji_api_tianjireport_collectuser_response']['redirectUrl']]);
                    } else {
                        return json_encode(['status' => '100', 'msg' => $result['msg']]);
                    }
                }*/
                if(!$product['other_info']){
                    $apply = new  Apply();
                    $other_info = '';
                    // 保存基本信息，清空$_SESSION['apply_info']
                    $res = $apply->saveInfo(Yii::$app->session['apply_info'],$other_info);
                    if(!$res){
                        return json_encode(['status'=>100,'msg'=>'申请失败，请重试']);
                    }
                    // 保存成功 ，清空$_SESSION['apply_info']
                    Yii::$app->session->remove('apply_info');
                }
                return json_encode(['status'=>200,'msg'=>$res['msg']]);
            }else{
                return json_encode(['status'=>100,'msg'=>$res['msg']]);
            }
        }
    }

    // 天机同步回调
    public function actionReturnUrl(){
        if(!Yii::$app->session['is_login']){
            return $this->redirect(['member/login']);
        }
     //   $user_id = Yii::$app->request->get('userId');
        $member = Member::findOne(Yii::$app->session['id']);
        $product = AdminProduct::findOne(Yii::$app->session['product_id']);
      //  $outUniqueId	 = Yii::$app->request->get('outUniqueId	');
       return $this->render('other-info',['product'=>$product,$member=>$member]);
    }

    // 申请人其他信息
    public function actionOtherInfo()
    {
        if(!Yii::$app->session['is_login']){
            return $this->redirect(['member/login']);
        }
        $product_id = (int)$this->get('product_id');
        if(!$product_id){
            throw new \Exception('参数错误');
        }
        $member = Member::findOne(Yii::$app->session['id']);
        $product = AdminProduct::findOne($product_id);
        if(!$product['other_info']){ // 没有其他字段
            return $this->redirect(['product/final','product_id'=>$product['id']]);
        }
        $fields = explode(',',$product['other_info']);
        return $this->render('other-info',['product'=>$product,'member'=>$member,'fields'=>$fields]);
    }

    // 保存其他相关详细
    public function actionSaveOtherInfo()
    {
        if(Yii::$app->request->isAjax){
            $post = $this->post();
            $product_id = (int)$post['product_id'];
            if(!$product_id){
                throw new \Exception('参数错误');
            }
            $product = AdminProduct::findOne($product_id);
            // 重新组装数据
            $fields = explode(',',$product['other_info']);
            unset($post['_csrf']);
            unset($post['product_id']);
            $other_info = json_encode(array_combine($fields,$post),JSON_UNESCAPED_UNICODE);
            $apply = new  Apply();
            // 保存基本信息，清空$_SESSION['apply_info']
            $res = $apply->saveInfo(Yii::$app->session['apply_info'],$other_info);
            if(!$res){
                return json_encode(['status'=>100,'msg'=>'保存失败，请稍后重试']);
            }
            Yii::$app->session->remove('apply_info');
            return json_encode(['status'=>200,'msg'=>'保存成功']);
        }
    }

    // 结果提示页
    public function actionFinal()
    {
        if(!Yii::$app->session['is_login']){
            return $this->redirect(['member/login']);
        }
        /*$product_id = (int)$this->get('product_id');
        if(!$product_id){
            throw new \Exception('参数错误');
        }*/
        $member = Member::findOne(Yii::$app->session['id']);
        $product = AdminProduct::findOne(1);
        return $this->render('final',['member'=>$member,'product'=>$product]);
    }
    



}