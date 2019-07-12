<?php

namespace mobile\controllers;

use Yii;
use backend\models\AdminBanner;
use backend\models\AdminProduct;
use yii\helpers\Url;

class IndexController extends BaseController
{
    public $limit = 5;
    //首页
    public function actionIndex(){
        $banners  = AdminBanner::find()->where(['status'=>1])->asArray()->all();
        $products = AdminProduct::find()->orderBy(['sort'=>SORT_DESC,'id'=>SORT_DESC])->limit($this->limit)->all();
        return $this->render('index',[
            'banners'=>json_encode($banners),
            'products'=>$products
        ]);
    }

    // 加载更多
    public function actionMore()
    {
        if(Yii::$app->request->isAjax){
            $offset = $this->get('offset');
            $products = AdminProduct::find()->orderBy(['sort'=>SORT_DESC,'id'=>SORT_DESC])
                ->offset($offset)->limit($this->limit)->asArray()->all();
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
}