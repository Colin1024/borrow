<?php
namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_system".
 *
 * @property integer $id
 * @property string $key
 * @property string $title
 * @property string $content
 * @property integer $creat_time
 */
class AdminSystem extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_system';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'title', 'content', 'create_time'], 'required'],
            [['creat_time'], 'integer'],
            [['key'], 'string', 'max' => 20],
            [['title'], 'string', 'max' => 32],
            [['content'], 'string', 'max' => 500],
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

    // 更新
    public function edit($data){
        unset($data['_csrf']);
      //  var_dump($data);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            foreach ($data as $key=>$val){
                AdminSystem::updateAll(['content'=>$val],['key'=>$key]);
            }
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }
        return true;
    }
}
