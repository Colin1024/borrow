<?php
namespace backend\models;

use Yii;


class AdminCommission extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_commission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tel', 'product', 'is_match', 'is_allot','create_time'], 'required'],
            [['loan', 'repayment', 'commission'], 'number'],
            [['is_match', 'is_allot', 'create_time'], 'integer'],
            [['tel'], 'string', 'max' => 11],
            [['product'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'pic' => 'Pic',
            'type' => 'Type',
            'sort' => 'Sort',
            'status' => 'Status',
            'create_time' => 'Create Time',
        ];
    }

    // 查询条件
    public static function condition($query,$search){
        if(!empty($search['tel'])){
            $query->andWhere(['like','tel',$search['tel']]);
        }
        if(!empty($search['product'])){
            $query->andWhere(['like','product',$search['product']]);
        }
        if(!empty($search['is_match'])){
            $query->andWhere(['is_match'=>$search['is_match']]);
        }
        if(!empty($search['is_allot'])){
            $query->andWhere(['is_allot'=>$search['is_allot']]);
        }
        if(!empty($search['b_time'])){
            $search['b_time'] = strtotime($search['b_time'].' 00:00:00');
            $query->andWhere(['>=','create_time',$search['b_time']]);
        }
        if(!empty($search['e_time'])){
            $search['e_time'] = strtotime($search['e_time'].' 23:59:59');
            $query->andWhere(['<=','create_time',$search['e_time']]);
        }
        return $query;
    }

    // 添加导入数据
    public function import($data)
    {
        if(!$data){
            return false;
        }
        $new_data =[];
        $i = 0;
        foreach ($data as $val){
            $member = AdminMember::findOne(['tel'=>$val['tel']]);
            $new_data[$i]['tel'] = $val['tel'];
            $new_data[$i]['product'] = $val['product'];
            $new_data[$i]['loan'] = $val['loan'];
            $new_data[$i]['repayment'] = $val['repayment'];
            $new_data[$i]['commission'] = $val['commission'];
            if($member){
                $new_data[$i]['is_match'] = 2;
            }else{
                $new_data[$i]['is_match'] = 1;
            }
            $new_data[$i]['is_allot'] = 1;
            $new_data[$i]['create_time'] = time();
            $i++;
        }
        // 批量添加数据
        $res = Yii::$app->db->createCommand()->batchInsert('admin_commission',['tel','product','loan','repayment','commission','is_match','is_allot','create_time'],$new_data)->execute();
        if($res){
            return true;
        }
        return false;
    }

    // 分配佣金
    public function allot($id)
    {
        $commission_one = AdminSystem::findOne(['key'=>'commission_one'])->content;
        $commission_two = AdminSystem::findOne(['key'=>'commission_two'])->content;
        $commission_three = AdminSystem::findOne(['key'=>'commission_three'])->content;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model = AdminCommission::findOne($id);
            $member = AdminMember::findOne(['tel'=>$model->tel]);
            if($member->pre_id){ // 一级
                $member_one = AdminMember::findOne($member->pre_id);
                $commission_one_money = $model->commission * $commission_one;
                $member_one->balance += $commission_one_money;
                $member_one->save();
                // 资金记录
                $money_log = new AdminMoneyLog();
                $money_log->mem_id = $member_one->id;
                $money_log->money = $commission_one_money;
                $money_log->way = 1;
                $money_log->type = 1;
                $money_log->balance = $member_one->balance;
                $money_log->remark = '一级返佣[增加]';
                $money_log->create_time = time();
                $money_log->save();
                if($member_one->pre_id){ // 二级
                    $member_two = AdminMember::findOne($member_one->pre_id);
                    $commission_two_money = $model->commission * $commission_two;
                    $member_two->balance += $commission_two_money;
                    $member_two->save();
                    // 资金记录
                    $money_log = new AdminMoneyLog();
                    $money_log->mem_id = $member_two->id;
                    $money_log->money = $commission_two_money;
                    $money_log->way = 1;
                    $money_log->type = 1;
                    $money_log->balance = $member_two->balance;
                    $money_log->remark = '二级返佣[增加]';
                    $money_log->create_time = time();
                    $money_log->save();
                    if($member_two->pre_id){ // 三级
                        $member_three = AdminMember::findOne($member_two->pre_id);
                        $commission_three_money = $model->commission * $commission_three;
                        $member_three->balance += $commission_three_money;
                        $member_three->save();
                        // 资金记录
                        $money_log = new AdminMoneyLog();
                        $money_log->mem_id = $member_three->id;
                        $money_log->money = $commission_three_money;
                        $money_log->way = 1;
                        $money_log->type = 1;
                        $money_log->balance = $member_three->balance;
                        $money_log->remark = '三级返佣[增加]';
                        $money_log->create_time = time();
                        $money_log->save();
                    }
                }
            }
            $model->is_allot = 2;
            $model->save();
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }
        return true;
    }


}
