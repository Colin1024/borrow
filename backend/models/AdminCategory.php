<?php
namespace backend\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "admin_category".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $name
 * @property integer $level
 * @property integer $create_time
 */
class AdminCategory extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['pid', 'integer'],
            ['name','required'],
            ['name', 'string', 'max' => 32],
            ['level', 'required'],
            ['create_time','safe']
        ];
    }

    // 查询条件
    public static function condition($query,$search){
        if(isset($search['name']) && !empty($search['name'])){
            $query->andWhere(['like','name',$search['name']]);
        }
        return $query;
    }

    // 获取所有分类
    public static function categoryTree()
    {
        $category = self::find()->orderBy(['level'=>SORT_ASC])->all();
        $category = self::addPrefix($category);
        $top_level = ['pid'=>'0','name'=>'--顶级分类--','level'=>0];
        array_unshift($category,$top_level);
        return $category;
    }

    // 分类添加前缀
    public static function addPrefix($data){
        if(empty($data)){
            return $data;
        }
       foreach ($data as $key=>$val){
           $prefix = '|--&nbsp;&nbsp;&nbsp;&nbsp;';
           $num = count(explode('-',$val['level']))-2;
           $data[$key]['name'] = str_repeat($prefix,$num).$val['name'];
       }
      return $data;
    }

    // 获取分类树
    public static function getTree($data,$pid=0){
        $tree = [];
        foreach ($data as $key=>$val){
            if($val['pid'] == $pid){
                $val['pid'] = self::getTree($data,$val['id']);
                $tree[] = $val;
            }
        }
        return $tree;
    }

    // 获取JsTree树
    public static function getJsTree($data,$pid=0){
        $tree = [];
        foreach ($data as $key=>$val){
            if($val['pid'] == $pid){
                $val['children'] = self::getJsTree($data,$val['id']);
                $val['text'] = $val['name'];
                $model= self::findOne(['pid'=>$val['id']]);
                if(!$model){
                    $val['icon']='fa fa-file text-green';
                    unset($val['children']);
                }
                $tree[] = $val;
            }
        }
        return $tree;
    }


    // 添加
    public function create($data)
    {
        if(!$data){
            return false;
        }
        $arr = explode('-',$data['level']);
        $pid = end($arr);
        $transition = Yii::$app->db->beginTransaction();
        try{
            $this->pid = $pid;
            $this->name = $data['name'];
            $this->level = $data['level'];
            $this->create_time = time();
            $this->save();
            // 更新为正确的分类级别
            $id =  $this->id;
            $level = $this->level.'-'.$id;
            $this->level = $level;
            $this->save(false);
            $transition->commit();
            return true;
        }catch(Exception $e){
            $transition->rollBack();
            return false;
        }
    }

    // 更新
    public function renewal($data){
        if(empty($data) || !is_array($data)){
            return false;
        }
        $model = self::findOne(['id'=>$data['id']]);
        $model->name = $data['name'];
        $model->create_time = time();
        if($model->save()){
            return true;
        }else{
            return false;
        }
    }

}
