<?php
namespace backend\models;

use Yii;

class AdminWithdrawal extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%withdrawal}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mem_id', 'money', 'create_time'], 'required'],
            [['mem_id', 'status', 'create_time'], 'integer'],
            [['money'], 'number']
        ];
    }


    // 查询条件
    public static function condition($query,$search){
        if(isset($search['name']) && !empty($search['name'])){
            $query->andWhere(['or',['like','admin_member.name',$search['name']],['like','admin_member.tel',$search['name']]]);
        }
        if(isset($search['b_time']) && !empty($search['b_time'])){
            $search['b_time'] = strtotime($search['b_time'].' 00:00:00');
            $query->andWhere(['>=','admin_withdrawal.create_time',$search['b_time']]);
        }
        if(isset($search['e_time']) && !empty($search['e_time'])){
            $search['e_time'] = strtotime($search['e_time'].' 23:59:59');
            $query->andWhere(['<=','admin_withdrawal.create_time',$search['e_time']]);
        }
        return $query;
    }

    // 审核
    public function audit($id,$status)
    {
        if(!$id){
            return false;
        }
        $trans = Yii::$app->db->beginTransaction();
        try{
            $model = AdminWithdrawal::findOne($id);
            $model->status = $status;
            $model->save();
            if($status == 3){ // 审核不通过;
                $member =  AdminMember::findOne($model->mem_id);
                $member->balance += $model->money;
                $member->save(false);
                // 资金记录
                $money_log = new AdminMoneyLog();
                $money_log->mem_id = $model->mem_id;
                $money_log->money = $model->money;
                $money_log->way = 2;//提现
                $money_log->type = 1;
                $money_log->balance = $member->balance;
                $money_log->remark = '提现审核失败，返回提现申请金额[增加]';
                $money_log->create_time = time();
                $money_log->save(false);
            }
            $trans->commit();
            return true;
        }catch (\Exception $e){
            $trans->rollBack();
            return false;
        }
    }

    // 关联用户表
    public function getMember()
    {
        return $this->hasOne(AdminMember::className(),['id'=>'mem_id']);
    }


}
