<?php
namespace mobile\controllers;

use Yii;
use backend\models\AdminSystem;

class PublicController extends BaseController
{
    // 腾讯云短信发送接口
    public static function sendSms($phone, $tpl_id, $random)
    {
        $appid = AdminSystem::findOne(['key'=>'sms_appid'])->content;
        $appkey = AdminSystem::findOne(['key'=>'sms_appkey'])->content;
        $sj = 3;
        $curTime = time();
        $wholeUrl = "https://yun.tim.qq.com/v5/tlssmssvr/sendsms?sdkappid=".$appid."&random=" . $random;
        // 按照协议组织 post 包体
        $data = new \stdClass();
        $tel = new \stdClass();
        $tel->nationcode = "" . "86";
        $tel->mobile = "" . $phone;
        $data->tel = $tel;
        $data->sig = hash("sha256",
            "appkey=".$appkey."&random=" . $random . "&time="
            . $curTime . "&mobile=" . $phone, FALSE);
        $data->tpl_id = $tpl_id;
        $data->params = array($random, $sj);
        $data->time = $curTime;
        //$data->sign = '云肆网络';//如果只有一个则不需要签名
        $data->extend = '';
        $data->ext = '';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $wholeUrl);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $ret = curl_exec($curl);
        $res = json_decode($ret, true);
        if ($res['errmsg'] == 'OK') {//发送成功
            return ['status'=>200,'msg'=>'发送成功'];
        } else {
            return ['status'=>100,'msg'=>$res['errmsg']];
        }
    }

    // 银行卡四要素验证
    // 购买链接：https://market.cloud.tencent.com/products/5295#
    public static function bankCard($name, $idcard, $bankcard, $mobile)
    {
        $secret_id = AdminSystem::findOne(['key'=>'bank_card4_secret_id'])->content;
        $secret_key = AdminSystem::findOne(['key'=>'bank_card4_secret_key'])->content;
        $dateTime = gmdate("I d F Y H:i:s");
        $SecretId = $secret_id;
        $SecretKey = $secret_key;
        $srcStr = "date: " . $dateTime . "\n" . "source: " . "bankcard4";
        $Authen = 'hmac id="' . $SecretId . '", algorithm="hmac-sha1", headers="date source", signature="';
        $signStr = base64_encode(hash_hmac('sha1', $srcStr, $SecretKey, true));
//echo $signStr;
        $Authen = $Authen . $signStr . "\"";
//        echo $Authen;
#echo '</br>';

        $url = 'https://service-m5ly0bzh-1256140209.ap-shanghai.apigateway.myqcloud.com/release/bank_card4/verify';
        $bodys = "?bankcard=$bankcard&idcard=$idcard&name=$name&mobile=$mobile";
        $headers = array(
            'Host:service-m5ly0bzh-1256140209.ap-shanghai.apigateway.myqcloud.com',
            'Accept:text/html, */*; q=0.01',
            'Source: bankcard4',
            'Date: ' . $dateTime,
            'Authorization: ' . $Authen,
            'X-Requested-With: XMLHttpRequest',
            'Accept-Encoding: gzip, deflate, sdch'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . $bodys);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $data = curl_exec($ch);
//        echo "ss========:".$data;
//        if (curl_errno($ch)) {
//            echo 1;
//            print "Error: " . curl_error($ch);
//        } else {
        // Show me the result
        curl_close($ch);
        return json_decode($data, true);
//        }
    }

    // 运营商认证接口
    public static function collectuser($userId,$name,$idNumber,$tel,$outUniqueId){
        error_reporting(E_ALL^E_NOTICE);
        include dirname(dirname(__DIR__)).'/extend/tianji/OpenapiClient.php';

        $sample= new \OpenapiClient();
        $sample->setMethod('tianji.api.tianjireport.collectuser');
        $sample->setField('type', 'mobile');
        $sample->setField('platform', 'web');
        $sample->setField('userId', $userId);
        $sample->setField('outUniqueId', $outUniqueId);
        $sample->setField('name', $name);
        $sample->setField('phone', $tel);
        $sample->setField('idNumber', $idNumber);
        $sample->setField('notifyUrl', Yii::$app->request->hostInfo.'/index.php?r=index%2Fnotifyurl');
        $sample->setField('returnUrl', Yii::$app->request->hostInfo.'/index.php?r=index%2Freturn-url');
        $sample->setField('version', '2.0');
        $ret= $sample->execute();
        return $ret;
    }

    // 数字金额转换成中文大写金额
    public static function num_to_rmb($num){
        $c1 = "零壹贰叁肆伍陆柒捌玖";
        $c2 = "分角元拾佰仟万拾佰仟亿";
        //精确到分后面就不要了，所以只留两个小数位
        $num = round($num, 2);
        //将数字转化为整数
        $num = $num * 100;
        if (strlen($num) > 10) {
            return "金额太大，请检查";
        }
        $i = 0;
        $c = "";
        while (1) {
            if ($i == 0) {
                //获取最后一位数字
                $n = substr($num, strlen($num)-1, 1);
            } else {
                $n = $num % 10;
            }
            //每次将最后一位数字转化为中文
            $p1 = substr($c1, 3 * $n, 3);
            $p2 = substr($c2, 3 * $i, 3);
            if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
                $c = $p1 . $p2 . $c;
            } else {
                $c = $p1 . $c;
            }
            $i = $i + 1;
            //去掉数字最后一位了
            $num = $num / 10;
            $num = (int)$num;
            //结束循环
            if ($num == 0) {
                break;
            }
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
            //utf8一个汉字相当3个字符
            $m = substr($c, $j, 6);
            //处理数字中很多0的情况,每次循环去掉一个汉字“零”
            if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j = $j-3;
                $slen = $slen-3;
            }
            $j = $j + 3;
        }
        //这个是为了去掉类似23.0中最后一个“零”字
        if (substr($c, strlen($c)-3, 3) == '零') {
            $c = substr($c, 0, strlen($c)-3);
        }
        //将处理的汉字加上“整”
        if (empty($c)) {
            return "零元整";
        }else{
            return $c . "整";
        }
    }




}