<?php
namespace backend\models;

use Yii;

class AdminApply extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%apply}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mem_id', 'product_id', 'money', 'use', 'time_limit', 'signature', 'protocol', 'b_time', 'e_time', 'status', 'create_time'], 'required'],
            [['mem_id', 'product_id', 'money', 'time_limit', 'b_time', 'e_time', 'status', 'create_time'], 'integer'],
            [['signature', 'protocol'], 'string'],
            [['use'], 'string', 'max' => 32]
        ];
    }


    // 查询条件
    public static function condition($query,$search){
        if(isset($search['name']) && !empty($search['name'])){
            $query->andWhere(['or',['like','admin_member.name',$search['name']],['like','admin_member.tel',$search['name']]]);
        }
        if(isset($search['product_name']) && !empty($search['product_name'])){
            $query->andWhere(['like','admin_product.name',$search['product_name']]);
        }
        if(isset($search['b_time']) && !empty($search['b_time'])){
            $search['b_time'] = strtotime($search['b_time'].' 00:00:00');
            $query->andWhere(['>=','admin_member.create_time',$search['b_time']]);
        }
        if(isset($search['e_time']) && !empty($search['e_time'])){
            $search['e_time'] = strtotime($search['e_time'].' 23:59:59');
            $query->andWhere(['<=','admin_member.create_time',$search['e_time']]);
        }
        return $query;
    }

    // 关联产品表
    public function getProduct()
    {
        return $this->hasOne(AdminProduct::className(),['id'=>'product_id']);
    }

    // 关联用户表
    public function getMember()
    {
        return $this->hasOne(AdminMember::className(),['id'=>'mem_id']);
    }


}
