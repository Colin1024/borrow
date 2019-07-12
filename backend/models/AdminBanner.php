<?php
namespace backend\models;

use Yii;


class AdminBanner extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_banner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'pic', 'create_time'], 'required'],
            [['type', 'sort', 'status', 'create_time'], 'integer'],
            [['title'], 'string', 'max' => 32],
            [['pic'], 'string', 'max' => 100],
            [['link'], 'string', 'max' => 255]
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
        if(!empty($search['title'])){
            $query->andWhere(['like','title',$search['title']]);
        }
        if(!empty($search['status'])){
            $query->andWhere(['status'=>$search['status']]);
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

    // 添加
    public function create($data)
    {
        if(!$data){
            return false;
        }
        $this->title = $data['title'];
        $this->pic = $data['pic'];
        $this->status = $data['status'];
        $this->link = $data['link'];
        $this->sort = $data['sort'];
        $this->type = 1;
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
        $model->title = $data['title'];
        $model->pic = $data['pic'];
        $model->status = $data['status'];
        $model->link = $data['link'];
        $model->sort = $data['sort'];
        $model->type = 1;
        $model->create_time = time();
        if($model->save()){
            return true;
        }
        return false;
    }

}
