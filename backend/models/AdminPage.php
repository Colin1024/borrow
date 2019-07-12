<?php
namespace backend\models;

use Yii;


class AdminPage extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'title', 'content', 'create_time'], 'required'],
            [['content'], 'string'],
            [['create_time'], 'integer'],
            [['key'], 'string', 'max' => 20],
            [['title'], 'string', 'max' => 32],
            [['key'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'title' => 'Title',
            'content' => 'Content',
            'create_time' => 'Create Time',
        ];
    }

    // 查询条件
    public static function condition($query,$search){
        if(!empty($search['title'])){
            $query->andWhere(['like','title',$search['title']]);
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
        $this->key = $data['key'];
        $this->title = $data['title'];
        $this->content = $data['content'];
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
        $model->key = $data['key'];
        $model->title = $data['title'];
        $model->content = $data['content'];
        if($model->save()){
            return true;
        }
        return false;
    }

}
