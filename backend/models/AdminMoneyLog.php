<?php
namespace backend\models;


class AdminMoneyLog extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%money_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mem_id', 'money', 'way', 'type', 'balance', 'remark', 'create_time'], 'required'],
            [['mem_id', 'way', 'type', 'create_time'], 'integer'],
            [['money', 'balance'], 'number'],
            [['remark'], 'string', 'max' => 64]
        ];
    }


    // 查询条件
    public static function condition($query,$search){
        if(isset($search['name']) && !empty($search['name'])){
            $query->andWhere(['or',['like','admin_member.name',$search['name']],['like','admin_member.tel',$search['name']]]);
        }
        if(isset($search['way']) && !empty($search['way'])){
            $query->andWhere(['admin_money_log.way'=>$search['way']]);
        }
        if(isset($search['type']) && !empty($search['type'])){
            $query->andWhere(['admin_money_log.type'=>$search['type']]);
        }
        if(isset($search['b_time']) && !empty($search['b_time'])){
            $search['b_time'] = strtotime($search['b_time'].' 00:00:00');
            $query->andWhere(['>=','admin_money_log.create_time',$search['b_time']]);
        }
        if(isset($search['e_time']) && !empty($search['e_time'])){
            $search['e_time'] = strtotime($search['e_time'].' 23:59:59');
            $query->andWhere(['<=','admin_money_log.create_time',$search['e_time']]);
        }
        return $query;
    }

    // 关联用户表
    public function getMember()
    {
        return $this->hasOne(AdminMember::className(),['id'=>'mem_id']);
    }

}
