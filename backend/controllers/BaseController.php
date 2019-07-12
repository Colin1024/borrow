<?php
namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use backend\models\AdminLog;
use common\utils\CommonFun;
use yii\helpers\StringHelper;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
class BaseController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if($this->verifyPermission($action) == true){
                return true;
            }
        }
        return false;
    }

    private function verifyPermission($action){
        $route = $this->route;
        // 检查是否已经登录
        if(Yii::$app->user->isGuest){
            $allowUrl = ['site/index', 'site/login'];
            if(in_array($route, $allowUrl) == false){
                $this->redirect(Url::toRoute('site/index'));
                return false;
            }
        }
        else{
            $system_rights = Yii::$app->user->identity->getSystemRights();
            $loginAllowUrl = ['site/index', 'site/logout', 'site/psw', 'site/psw-save','site/error','base/clear-cache']; // 加入错误页面
            if(in_array($route, $loginAllowUrl) == false){
               if((empty($system_rights) == true || empty($system_rights[$route]) == true)){
                    header("Content-type: text/html; charset=utf-8");
                    exit(json_encode(['status'=>100,'msg'=>'没有权限访问'.$route],JSON_UNESCAPED_UNICODE));
               }
               $rights = $system_rights[$route];
               if($route != 'system-log/index'){
                    $systemLog = new AdminLog();
                    $systemLog->url = $route;
                    $systemLog->controller_id = $action->controller->id;
                    $systemLog->action_id = $action->id;
                    $systemLog->module_name = $rights['module_name'];
                    $systemLog->func_name = $rights['menu_name'];
                    $systemLog->right_name = $rights['right_name'];
                    $systemLog->create_date = date('Y-m-d H:i:s');
                    $systemLog->create_user = Yii::$app->user->identity->uname;
                    $systemLog->client_ip = CommonFun::getClientIp();
                    $systemLog->save();
               }
            }
        }
        return true;
    }

    protected function getAllController(){
        $className = get_class($this);
        $mn = explode('\\', $className);
        array_pop($mn);
        $classNameSpace = implode('\\', $mn);
        $dir = dirname(__FILE__);
        $classfiles = glob ( $dir . "/*Controller.php" );
        $controllerDatas = [];
        foreach($classfiles as $file){
            $info = pathinfo($file);
            $controllerClass = $classNameSpace . '\\' . $info[ 'filename' ];
            $controllerDatas[$info[ 'filename' ]] = $controllerClass;
        }
        $rightActionData = [];
        foreach($controllerDatas as $c){
            if(StringHelper::startsWith($c, 'backend\controllers') == true && $c != 'backend\controllers\BaseController'){
                $controllerName = substr($c, 0, strlen($c) - 10);
                $cUrl = Inflector::camel2id(StringHelper::basename($controllerName));
                $methods = get_class_methods($c);
                $rightTree = ['text'=>$c, 'selectable'=>false, 'state'=>['checked'=>false], 'type'=>'r'];
                foreach($methods as $m){
                    if($m != 'actions' && StringHelper::startsWith($m, 'action') !== false){
                        $actionName = substr($m, 6, strlen($m));
                        $aUrl = Inflector::camel2id($actionName);
                        $actionTree = ['text'=>$aUrl . "&nbsp;&nbsp;($cUrl/$aUrl)", 'c'=>$cUrl, 'a'=>$aUrl, 'selectable'=>true, 'state'=>['checked'=>false], 'type'=>'a'];
                        if(isset($rightUrls[$cUrl.'/'.$aUrl]) == true){
                            $actionTree['state']['checked'] = true;
                            $rightTree['state']['checked'] = true;
                        }
                        $rightTree['nodes'][] = $actionTree;
                    }
                }
                $rightActionData[] = $rightTree;
            }
        }
        return $rightActionData;
    }

    // 清除缓存
    public function actionClearCache()
    {
        $cache = Yii::$app->cache;
        if($cache->flush()){
            return json_encode(['status'=>200,'msg'=>'清除成功']);
        }else{
            return json_encode(['status'=>100,'msg'=>'清除失败']);
        }
    }

    // 统一获取post数据
    public function post($name = null, $defaultValue = null){
        $post = Yii::$app->request->post($name, $defaultValue);
        if(is_array($post)){
            $post = array_map('trim',$post);
            $post = array_map('htmlspecialchars',$post);
        }else{
            $post = htmlspecialchars(trim($post));
        }
        return $post;
    }

    // 统一获取get数据
    public function get($name = null, $defaultValue = null)
    {
        $get = Yii::$app->request->get($name, $defaultValue);
        if(is_array($get)){
            $get = array_map('trim',$get);
            $get = array_map('htmlspecialchars',$get);
        }else{
            $get = htmlspecialchars(trim($get));
        }
        return $get;
    }

    // 调试函数
    public function dd(){
        $param = func_get_args();
        foreach ($param as $p)  {
            VarDumper::dump($p, 10, true);
        }
        exit(1);
    }


    // csv文件导出
    public static function csvExport($data){
        $csvFileName = date('YmdHis').rand(111111,999999).'.csv';
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$csvFileName.'"');
        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
        header('Expires: Mon,26 Jul 1997 05:00:00 GMT');
        header('Content-Transfer-Encoding: binary');
        echo implode("\r\n",$data);exit;
    }

    // 解析csv
    public static function inputCsv($handle){
        $out = [];
        $n = 0;
        while($data = fgetcsv($handle,10000)){
            $num = count($data);
            for($i = 0; $i < $num; $i++){
                $out[$n][$i] = $data[$i];
            }
            $n++;
        }
        return $out;
    }

    // 文件下载
    public static function fileDownload($file_dir,$file_name)
    {
        //检查文件是否存在
        if (!file_exists($file_dir . $file_name)) {
            header('HTTP/1.1 404 NOT FOUND');
        } else {
            //以只读和二进制模式打开文件
            $file = fopen($file_dir . $file_name, "rb");

            //告诉浏览器这是一个文件流格式的文件
            Header("Content-type: application/octet-stream");
            //请求范围的度量单位
            Header("Accept-Ranges: bytes");
            //Content-Length是指定包含于请求或响应中数据的字节长度
            Header("Accept-Length: " . filesize($file_dir . $file_name));
            //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
            Header("Content-Disposition: attachment; filename=" . $file_name);

            //读取文件内容并直接输出到浏览器
            echo fread($file, filesize($file_dir . $file_name));
            fclose($file);
            exit ();
        }
    }



}





?>
