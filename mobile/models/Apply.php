<?php
namespace mobile\models;

use Yii;
class Apply extends Base
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

    public function scenarios()
    {
        return [
          'apply_info'=>['money','time_limit','use','product_id']
        ];
    }

    public function attributeLabels()
    {
        return [
          'money'=>'申请金额',
          'time_limit'=>'申请期限',
          'use'=>'申请用途',
        ];
    }

    // 验证申请信息
    public function checkApplyInfo($data)
    {
        $this->scenario = 'apply_info';
        $this->mem_id = Yii::$app->session['id'];
        $this->money = $data['money'];
        $this->time_limit = $data['time_limit'];
        $this->use = $data['use'];
        $this->product_id = $data['product_id'];
        if(!$this->validate()){
            $errors = $this->getFirstErrors();
            foreach ($errors as $key=>$val){
               $msg = $val;
               break;
            }
            return ['status'=>100,'msg'=>$msg];
        }else{
            return ['status'=>200,'msg'=>'验证成功'];
        }
    }

   // 保存基本信息
    public function saveInfo($apply_info,$other_info)
    {
        $this->mem_id = Yii::$app->session['id'];
        $this->product_id = $apply_info['product_id'];
        $this->money = $apply_info['money'];
        $this->time_limit = $apply_info['time_limit'];
        $this->use = $apply_info['use'];
        $this->other_info_val = $other_info;
        $this->create_time = time();
        $this->b_time = time();
        $this->e_time = strtotime("+{$apply_info['time_limit']} day");
        if($this->save(false)){ // 无需重复验证
            return true;
        }else{
            return false;
        }
    }


}