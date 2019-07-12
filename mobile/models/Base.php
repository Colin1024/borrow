<?php
namespace mobile\models;

use yii\db\ActiveRecord;
use yii\helpers\VarDumper;
class Base extends ActiveRecord
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
