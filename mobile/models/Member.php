<?php
namespace mobile\models;

use Yii;
class Member extends Base
{
    public $re_password;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','id_card', 'bank_card', 'bank_tel','create_time'], 'required'],
            [['pre_id', 'is_auth', 'tel_auth', 'create_time'], 'integer'],
            [['name', 'bank_card'], 'string', 'max' => 32],
            ['tel','required'],
            ['password','required'],
            ['re_password','required'],
            ['re_password','compare','compareAttribute'=>'password','message'=>'两次输入的密码不一致'],
            [['tel', 'bank_tel'], 'string', 'max' => 11],
            [['id_card'], 'string', 'max' => 18],
            [['tel'], 'unique','message'=>'该手机号已注册,请登录'],
            [['balance'], 'number'],
            [['tel','bank_tel'],'match','pattern'=>'/^1[3,5,7,8,9]\d{9}$/'],
            ['id_card','match','pattern'=>'/^\d{18}|\d{17}X$/i'],
            ['bank_card','match','pattern'=>'/^([1-9]{1})(\d{14}|\d{18})$/']
        ];
    }

    // 场景
    public function scenarios()
    {
        return [
            'register'=>['tel','password','re_password'],
            'change-pwd'=>['password','re_password'],
            'check_auth'=>['real_name','id_card','bank_card','bank_tel']
        ];
    }

    // 标签
    public function attributeLabels()
    {
        return [
            'tel' => '手机号',
            'password' => '密码',
            're_password' => '确认密码',
            'real_name' => '真实姓名',
            'id_card' => '身份证号',
            'bank_card' => '银行卡号',
            'bank_tel' => '绑定手机号',
        ];
    }

    // 注册
    public function register($phone,$password,$re_password,$pre_id){
        $this->scenario = 'register';
        $this->tel = $phone;
        $this->re_password = $re_password;
        $this->password = $password;
        $this->pre_id = $pre_id;
        $this->invite_code = uniqid();
        $this->create_time = time();
        if(!$this->validate()){
            $errors = $this->getErrors();
            foreach ($errors as $key=>$val){
                $msg = $val[0];
            }
            return ['status'=>100,'msg'=>$msg];
        }
        $this->password = Yii::$app->security->generatePasswordHash($password);
        if($this->save(false)){
            $this->setLogin($this->tel);
            return ['status'=>200];
        }else{
            return ['status'=>100,'msg'=>'注册失败，请重新注册'];
        }
    }

    // 登录
    public function login($tel,$password)
    {
        $member = self::findOne(['tel'=>$tel]);
        $res = Yii::$app->security->validatePassword($password,$member->password);
        if($res){
            $this->setLogin($tel);
            return ['status'=>200];
        }else{
            return ['status'=>100];
        }
    }

    // 修改密码
    public function changePwd($phone,$password,$re_password)
    {
        $member = self::findOne(['tel'=>$phone]);
        $member->scenario = 'change-pwd';
        if(!$member){
            return ['status'=>100,'msg'=>'用户不存在'];
        }
        $member->password = $password;
        $member->re_password = $re_password;
        if(!$member->validate()){
            $errors = $member->getErrors();
            foreach ($errors as $key=>$val){
                $msg = $val[0];
            }
            return ['status'=>100,'msg'=>$msg];
        }
        $member->password = Yii::$app->security->generatePasswordHash($password);
        if($member->save(false)){
            $this->setLogin($member->tel);
            return ['status'=>200];
        }else{
            return ['status'=>100,'msg'=>'注册失败，请重新注册'];
        }

    }
    
    // 设置登录状态
    public function setLogin($phone)
    {
        $member = self::findOne(['tel'=>$phone]);
        Yii::$app->session['id'] = $member->id;
        Yii::$app->session['is_login'] = 1;
//        $lifetime = 30*24*3600; // 一个月
//        session_set_cookie_params($lifetime);
    }

    // 退出登录
    public function logout()
    {
        unset(Yii::$app->session['id']);
        unset(Yii::$app->session['is_login']);
    }

    // 检查身份认证
    public function checkAuth($data)
    {
        $this->scenario = 'check_auth';
        $this->name = $data['real_name'];
        $this->id_card = $data['id_card'];
        $this->bank_card = $data['bank_card'];
        $this->bank_tel = $data['bank_tel'];
        if(!$this->validate()){
            $errors = $this->getFirstErrors();
            foreach ($errors as $key=>$val){
                $msg = $val;
                break;
            }
            return ['status'=>100,'msg'=>$msg];
        }else{
            return ['status'=>200,'msg'=>'验证成功'];
        }
    }

    // 保存身份认证信息(或更新)
    public function saveAuth($data)
    {
        $model = self::findOne(Yii::$app->session['id']);
        $model->name = $data['real_name'];
        $model->id_card = $data['id_card'];
        $model->bank_card = $data['bank_card'];
        $model->bank_tel = $data['bank_tel'];
        if($model->save(false)){ // 已验证，无需重复验证
            return ['status'=>200,'msg'=>'保存成功'];
        }else{
            return ['status'=>100,'msg'=>'保存失败，请重试'];
        }
    }

}