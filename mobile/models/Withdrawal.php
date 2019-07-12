<?php
namespace mobile\models;

class Withdrawal extends Base{
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
            [['money'], 'number'],
            [['money'], 'compare','compareValue'=>0,'operator'=>'>','message'=>'金额必须大于0']
        ];
    }

    // 场景
    public function scenarios()
    {
        return [
          'create'=>['mem_id','money']
        ];
    }

    // 标签
    public function attributeLabels()
    {
        return [
            'mem_id'=>'用户id',
            'money'=>'金额',
        ];
    }

    // 添加
    public function create($mem_id,$money)
    {
        $this->scenario = 'create';
        $this->mem_id =  $mem_id;
        $this->money = $money;
        $this->status = 1; // 审核中
        $this->create_time = time();
        if(!$this->save()){
            $errors = $this->getErrors();
            foreach ($errors as $key=>$val){
                $msg = $val[0];
            }
            return ['status'=>100,'msg'=>$msg];
        }else{
            return ['status'=>200];
        }
    }


}