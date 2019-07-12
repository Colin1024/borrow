<?php
$strDir = dirname(__FILE__);
require_once($strDir . '/OpenapiDevBase.php');
class OpenapiClient
{
    protected $_bizData = null;
    protected $_method  = null;
    public function setField($key, $value)
    {/*{{{*/
        $this->_bizData[$key] = $value;
    } /*}}}*/

    public function setMethod($method)
    {/*{{{*/
        $this->_method = $method;
    } /*}}}*/

    public function  execute()
    {/*{{{*/
        $openapiDevBase = new OpenapiDevBase();
        return $openapiDevBase->sendRequest($this->_bizData, $this->_method);
    } /*}}}*/
}
//test 
//if ($argv && $argv[0] && realpath($argv[0]) === __FILE__)
//{
//    $sample= new OpenapiClient();
//    $sample->setMethod('tianji.api.zhongzhicheng.blacklist');
//    $sample->setField("name", "陆晓丹");
//    $sample->setField("idNumber", "320582198705205114");
//    $sample->setField("phone", "13731207099");
//    $ret= $sample->execute();
//    echo '<pre>'; print_r($ret);
//}
