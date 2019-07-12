<?php
namespace backend\models;

use Yii;

class AdminMember extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'tel', 'id_card', 'bank_card', 'bank_tel','create_time','invite_code'], 'required'],
            [['pre_id', 'is_auth', 'tel_auth', 'create_time'], 'integer'],
            [['name', 'bank_card'], 'string', 'max' => 32],
            [['tel', 'bank_tel'], 'string', 'max' => 11],
            [['id_card'], 'string', 'max' => 18],
            [['tel'], 'unique'],
            [['balance'], 'number'],
            [['tel','bank_tel'],'match','pattern'=>'/^1[3,5,7,8,9]\d{9}$/'],
            ['id_card','match','pattern'=>'/^\d{18}|\d{17}X$/i'],
            ['bank_card','match','pattern'=>'/^([1-9]{1})(\d{14}|\d{18})$/']
        ];
    }


    // 查询条件
    public static function condition($query,$search){
        if(!empty($search['name'])){
            $query->andWhere(['or',['like','admin_member.name',$search['name']],['like','admin_member.tel',$search['name']]]);
        }
        if(!empty($search['is_auth'])){
            $query->andWhere(['admin_member.is_auth'=>$search['is_auth']]);
        }
        if(!empty($search['tel_auth'])){
            $query->andWhere(['admin_member.tel_auth'=>$search['tel_auth']]);
        }
        if(!empty($search['b_time'])){
            $search['b_time'] = strtotime($search['b_time'].' 00:00:00');
            $query->andWhere(['>=','admin_member.create_time',$search['b_time']]);
        }
        if(!empty($search['e_time'])){
            $search['e_time'] = strtotime($search['e_time'].' 23:59:59');
            $query->andWhere(['<=','admin_member.create_time',$search['e_time']]);
        }
        return $query;
    }

    // 关联自身
    public function getMember()
    {
        return $this->hasOne(AdminMember::className(),['id'=>'pre_id'])->from(['member_pre'=>AdminMember::tableName()]);
    }

    // 添加
    public function create($data)
    {
       if(!$data){
           return false;
       }
       $this->pre_id = $data['pre_id'];
       $this->name = $data['name'];
       $this->tel = $data['tel'];
       $this->id_card = $data['id_card'];
       $this->bank_card = $data['bank_card'];
       $this->bank_tel = $data['bank_tel'];
       $this->is_auth = $data['is_auth'];
       $this->tel_auth = $data['tel_auth'];
       $this->invite_code = uniqid();
       $this->create_time = time();
       if($this->save()){
           return true;
       }
       return false;
    }

    // 更新
    public function edit($data){
        if(!$data){
            return false;
        }
        $model = self::findOne($data['id']);
        $model->pre_id = $data['pre_id'];
        $model->name = $data['name'];
        $model->tel = $data['tel'];
        $model->id_card = $data['id_card'];
        $model->bank_card = $data['bank_card'];
        $model->bank_tel = $data['bank_tel'];
        $model->is_auth = $data['is_auth'];
        $model->tel_auth = $data['tel_auth'];
        if($model->save()){
            return true;
        }
        return false;
    }

}
