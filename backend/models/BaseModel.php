<?php
namespace backend\models;

use yii\db\ActiveRecord;
use yii\helpers\VarDumper;
class BaseModel extends ActiveRecord
{
    // 调试函数
    public static function dd(){
        $param = func_get_args();
        foreach ($param as $p)  {
            VarDumper::dump($p, 10, true);
        }
        exit(1);
    }

   
}

