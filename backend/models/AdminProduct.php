<?php
namespace backend\models;

use Yii;

class AdminProduct extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cate_id', 'name', 'description', 'thumb', 'background','is_show', 'create_time'], 'required'],
            [['cate_id', 'auth_need', 'tel_auth_need', 'sort', 'create_time','apply_num','min_money','max_money'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['label'], 'string', 'max' => 255],
            [['lender_idcard'], 'match', 'pattern' => '/^\d{18}|\d{17}X$/i'],
            [['description'], 'string', 'max' => 255],
            [['thumb', 'background'], 'string', 'max' => 100],
            [['lender_name', 'lender_idcard'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cate_id' => '分类ID',
            'name' => 'Name',
            'description' => '描述',
            'thumb' => '缩略图',
            'background' => '背景',
            'auth_need' => '实名认证',
            'tel_auth_need' => '运营商认证',
            'lender_name' => '出借人姓名',
            'lender_idcard' => '身份证号',
            'sort' => '排序',
            'create_time' => '创建时间',
        ];
    }

    // 关联分类表
    public function getCategory()
    {
        return $this->hasOne(AdminCategory::className(),['id'=>'cate_id']);
    }

    // 查询条件
    public static function condition($query,$search){
        if(isset($search['name']) && !empty($search['name'])){
            $query->andWhere(['like','admin_product.name',$search['name']]);
        }
        if(isset($search['is_show']) && !empty($search['is_show'])){
            $query->andWhere(['admin_product.is_show'=>$search['is_show']]);
        }
        if(isset($search['b_time']) && !empty($search['b_time'])){
            $search['b_time'] = strtotime($search['b_time'].' 00:00:00');
            $query->andWhere(['>=','admin_product.create_time',$search['b_time']]);
        }
        if(isset($search['e_time']) && !empty($search['e_time'])){
            $search['e_time'] = strtotime($search['e_time'].' 23:59:59');
            $query->andWhere(['<=','admin_product.create_time',$search['e_time']]);
        }
        return $query;
    }

    // 添加
    public function create($data)
    {
       if(!$data){
           return false;
       }
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->thumb = $data['thumb'];
        $this->background = $data['background'];
        $this->is_show = $data['is_show'];
        $this->cate_id = $data['cate_id'];
        $this->auth_need = $data['auth_need'];
        $this->tel_auth_need = $data['tel_auth_need'];
        $this->other_info = $data['other_info'];
        $this->label = $data['label'];
        $this->apply_num = $data['apply_num'];
        $this->min_money = $data['min_money'];
        $this->max_money = $data['max_money'];
        $this->create_time = time();
        $this->apply_process = $data['apply_process'];
        $this->apply_condition = $data['apply_condition'];
        $this->guide = $data['guide'];
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
        $model->name = $data['name'];
        $model->description = $data['description'];
        $model->thumb = $data['thumb'];
        $model->background = $data['background'];
        $model->is_show = $data['is_show'];
        $model->cate_id = $data['cate_id'];
        $model->auth_need = $data['auth_need'];
        $model->tel_auth_need = $data['tel_auth_need'];
        $model->label = $data['label'];
        $model->apply_num = $data['apply_num'];
        $model->min_money = $data['min_money'];
        $model->max_money = $data['max_money'];
        $model->sort = $data['sort'];
        $model->apply_process = $data['apply_process'];
        $model->apply_condition = $data['apply_condition'];
        $model->guide = $data['guide'];
       // $model->create_time = time();
        if($model->save()){
            return true;
        }
        return false;
    }


}
