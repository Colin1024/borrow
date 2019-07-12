<?php
namespace mobile\controllers;

use backend\models\AdminMoneyLog;
use backend\models\AdminSystem;
use backend\models\AdminWithdrawal;
use common\models\UploadForm;
use mobile\models\Apply;
use mobile\models\Member;
use mobile\models\Withdrawal;
use Yii;
use QRcode;
use yii\web\UploadedFile;

class MemberController extends BaseController
{
    public $limit = 5;
    // 个人中心
    public function actionIndex()
    {
        $member = Member::findOne(Yii::$app->session['id']);
        return $this->render('index',['member'=>$member]);
    }
    
    // 账号密码登录
    public function actionLogin()
    {
        if (Yii::$app->request->isAjax) {
            $tel = $this->post('phone');
            $password = $this->post('password');
            $member = new Member();
            $res = $member->login($tel,$password);
            if($res['status'] == 200){
                return json_encode(['status'=>200,'msg'=>'登录成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'手机号或密码不正确']);
            }
        }
        return $this->render('login');
    }

    // 手机号快捷登录
    public function actionPhoneLogin()
    {
        if(Yii::$app->request->isAjax){
            $phone = $this->post('phone');
            $captcha = $this->post('captcha');
            if (Yii::$app->session['captcha_time'] < time()) {
                return json_encode(['status' => 100, 'msg' => '验证码已过期，请重新获取']);
            }
            if ($phone != Yii::$app->session['phone'] || $captcha != Yii::$app->session['captcha']) {
                return json_encode(['status' => 100, 'msg' => '手机号或验证码错误']);
            }
            $member = new Member();
            $member->setLogin($phone);
            return json_encode(['status'=>200,'msg'=>'登录成功']);
        }
        return $this->render('phone-login');
    }

    // 注册
    public function actionRegister()
    {
        // 邀请码
        $invite_code = $this->get('invite_code');
        $member = Member::findOne(['invite_code'=>$invite_code]);
        if($member){
            $pre_id = $member->id;
        }else{
            $pre_id = '';
        }
        Yii::$app->session['pre_id'] = $pre_id;
        if (Yii::$app->request->isAjax) {
            $phone = $this->post('phone');
            $captcha = $this->post('captcha');
            $password = $this->post('password');
            $re_password = $this->post('re_password');
            $pre_id  = $this->post('pre_id');
            if (Yii::$app->session['captcha_time'] < time()) {
                return json_encode(['status' => 100, 'msg' => '验证码已过期，请重新获取']);
            }
            if ($phone != Yii::$app->session['phone'] || $captcha != Yii::$app->session['captcha']) {
                return json_encode(['status' => 100, 'msg' => '手机号或验证码错误']);
            }
            $member = new Member();
            $res = $member->register($phone, $password, $re_password,$pre_id);
            Yii::$app->session->remove('pre_id');
            if ($res['status'] == 200) {
                return json_encode(['status'=>200,'msg'=>'注册成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>$res['msg']]);
            }
        }
        return $this->render('register');
    }

    // 头像上传
    public function actionAvatar()
    {
       if(Yii::$app->request->isAjax){
           if(!Yii::$app->session['is_login']){
               return $this->redirect(['member/login']);
           }
            $model = new UploadForm();
            $model->imageFile = UploadedFile::getInstanceByName('file');
            $res = $model->upload();
            $res_arr = json_decode($res,true);
            if($res_arr['status'] == 200){
                $member = Member::findOne(Yii::$app->session['id']);
                $member->head_img = $res_arr['path'];
                $member->save(false);// 非用户输入，不需验证
            }
            return $res;
       }
    }

    // 我的资金
    public function actionBalance()
    {
        if(!Yii::$app->session['is_login']){
            return $this->redirect(['member/login']);
        }
        $min_withdraw_money = AdminSystem::findOne(['key'=>'min_withdraw_money'])->content;
        $member = Member::findOne(Yii::$app->session['id']);
        $withdraws = AdminWithdrawal::find()->where(['mem_id'=>Yii::$app->session['id']])->orderBy(['id'=>SORT_DESC])->limit($this->limit)->all();
        if(Yii::$app->request->isAjax){
            $money = $this->post('money');
            $mem_id = Yii::$app->session['id'];
            if($money < $min_withdraw_money){
                return json_encode(['status'=>100,'msg'=>'提现金额不能小于最小提现金额']);
            }
            if($money > $member['balance']){
                return json_encode(['status'=>100,'msg'=>'提现金额不能大于账户余额']);
            }
            $withdrawal = new Withdrawal();
            $res = $withdrawal->create($mem_id,$money);
            if($res['status'] == 200){
                // 更改余额
                $member->balance -= $money;
                $member->save(false);
                // 资金记录
                $money_log = new AdminMoneyLog();
                $money_log->mem_id = Yii::$app->session['id'];
                $money_log->money = $money;
                $money_log->way = 2;//提现
                $money_log->type = 2;
                $money_log->balance = $member->balance;
                $money_log->remark = '提现[减少]';
                $money_log->create_time = time();
                $money_log->save(false);
                return json_encode(['status'=>200,'msg'=>'申请成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>$res['msg']]);
            }
        }
        return $this->render('balance',['member'=>$member,'withdraws'=>$withdraws,'min_withdraw_money'=>$min_withdraw_money]);
    }
    
    // 加载更多(我的资金)
    public function actionBalanceMore()
    {
        if(Yii::$app->request->isAjax){
            $offset = $this->get('offset');
            $withdraws = AdminWithdrawal::find()->where(['mem_id'=>Yii::$app->session['id']])->orderBy(['id'=>SORT_DESC])
                ->offset($offset)->limit($this->limit)->all();
            if(!$withdraws){
                return json_encode(['status'=>100,'msg'=>'没有更多数据']);
            }
            $offset = $offset + $this->limit;
            $content = '';
            $status = [1=>'待审核',2=>'审核通过',3=>'审核未通过',4=>'提现'];
            foreach ($withdraws as $withdraw){
                $content .= '<div class="record-item">';
                    $content .= '<span>'.date('Y/m/d',$withdraw['create_time']).'</span><span>'.$withdraw['money'].'</span><span style="color:red">'.$status[$withdraw['status']].'</span>';
                $content .= '</div>';
            }
            return json_encode(['status'=>200,'data'=>$content,'offset'=>$offset]);
        }
    }
    
    // 我的推广
    public function actionInvite()
    {
        if(!Yii::$app->session['is_login']){
            return $this->redirect(['member/login']);
        }
        $member = Member::findOne(Yii::$app->session['id']);
        $invite_num = Member::find()->where(['pre_id'=>Yii::$app->session['id']])->count();
        $invites = Member::find()->where(['pre_id'=>Yii::$app->session['id']])->orderBy(['id'=>SORT_DESC])->limit($this->limit)->all();
        $invite_url = Yii::$app->urlManager->createAbsoluteUrl(['member/register','invite_code'=>$member['invite_code']]);
        $invite_code = 'uploads/qrcode/'.$member['invite_code'].'.png';
        if(!file_exists($invite_code)){
            include dirname(dirname(__DIR__)).'/extend/qrcode/phpqrcode/phpqrcode.php';
            $invite_code = QRcode::png($invite_url,$invite_code,'H',4,5,false);
        }
        return $this->render('invite',['invite_num'=>$invite_num,'invites'=>$invites,'invite_url'=>$invite_url,'invite_code'=>$invite_code]);
    }

    // 加载更多(我的推广)
    public function actionInviteMore()
    {
        if(Yii::$app->request->isAjax){
            $offset = $this->get('offset');
            $invites = Member::find()->where(['pre_id'=>Yii::$app->session['id']])->orderBy(['id'=>SORT_DESC])
                ->offset($offset)->limit($this->limit)->all();
            if(!$invites){
                return json_encode(['status'=>100,'msg'=>'没有更多数据']);
            }
            $offset = $offset + $this->limit;
            $content = '';
            foreach ($invites as $invite){
                $content .= '<div class="record-item">';
                    $content .= '<span>'.date('Y/m/d',$invite['create_time']).'</span>';
                    $content .= '<span>'.$invite['tel'].'</span>';
                $content .= '</div>';
            }
            return json_encode(['status'=>200,'data'=>$content,'offset'=>$offset]);
        }
    }

    // 修改密码
    public function actionChangePwd()
    {
        if(!Yii::$app->session['is_login']){
            return $this->redirect(['member/login']);
        }
        if(Yii::$app->request->isAjax){
            $phone = $this->post('phone');
            $captcha = $this->post('captcha');
            $password = $this->post('password');
            $re_password = $this->post('re_password');
            if (Yii::$app->session['captcha_time'] < time()) {
                return json_encode(['status' => 100, 'msg' => '验证码已过期，请重新获取']);
            }
            if ($phone != Yii::$app->session['phone'] || $captcha != Yii::$app->session['captcha']) {
                return json_encode(['status' => 100, 'msg' => '手机号或验证码错误']);
            }
            $member = new Member();
            $res = $member->changePwd($phone, $password, $re_password);
            if($res['status'] == 200){
                return json_encode(['status'=>200,'msg'=>'密码修改成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>$res['msg']]);
            }
        }
        $member = Member::findOne(Yii::$app->session['id']);
        return $this->render('change-pwd',['member'=>$member]);
    }

    // 发送验证码
    public function actionCaptcha()
    {
        if(Yii::$app->request->isAjax){
            $phone = $this->get('phone');
            if($phone == ''){
                return json_encode(['status'=>100,'msg'=>'手机号不能为空']);
            }
            if(!preg_match('/^1[3,5,7,8,9]\d{9}$/',$phone)){
                return json_encode(['status'=>100,'msg'=>'手机号格式不正确']);
            }
            $captcha = mt_rand(100000,999999);
            $captcha = 123456;
            // 调试
            $tpl_id = AdminSystem::findOne(['key'=>'sms_yzm_tpl_id'])->content;
            Yii::$app->session['phone'] = $phone;
            Yii::$app->session['captcha'] = $captcha;
            Yii::$app->session['captcha_time'] = time() + 60*3;
           // $res = PublicController::sendSms($phone,$tpl_id,$captcha);
            $res['msg'] = 123;
            $res['status'] = 200;
            if($res['status'] == 200){
                return json_encode(['status'=>200,'msg'=>'发送成功']);
            }else{
                return json_encode(['status'=>100,'msg'=>'发送失败：'.$res['msg']]);
            }
        }
    }

    // 退出登录
    public function actionLogout()
    {
        $member = new Member();
        $member->logout();
        return $this->redirect(['member/index']);
    }
    
    // 我的借款
    public function actionApply()
    {
        if(!Yii::$app->session['is_login']){
            return $this->redirect(['member/login']);
        }
        $member = Member::findOne(Yii::$app->session['id']);
        $applys = Apply::find()->where(['mem_id'=>Yii::$app->session['id']])->orderBy(['id'=>SORT_DESC])->limit($this->limit)->all();
        $apply_num = Apply::find()->where(['mem_id'=>Yii::$app->session['id']])->count();
        return $this->render('apply',['member'=>$member,'applys'=>$applys,'apply_num'=>$apply_num]);
    }

    public function actionApplyMore()
    {
        if(Yii::$app->request->isAjax) {
            $offset = $this->get('offset');
            $applys = Apply::find()->where(['mem_id' => Yii::$app->session['id']])->orderBy(['id' => SORT_DESC])
                ->offset($offset)->limit($this->limit)->all();
            if (!$applys) {
                return json_encode(['status' => 100, 'msg' => '没有更多数据']);
            }
            $offset = $offset + $this->limit;
            $content = '';
            $status = [1=>'待审核',2=>'审核通过',3=>'审核未通过',4=>'已放款',5=>'已还款'];
            foreach ($applys as $apply){
                $content .= '<div class="record-item">';
                    $content .= '<span>'.date('Y/m/d',$apply['create_time']).'</span>';
                    $content .= '<span>'.$apply['money'].'</span>';
                    $content .= '<span style="color:red">'.$status[$apply['status']].'</span>';
                    $content .= '<span><a href="javascript:detail('.$apply['id'].')" style="color: rgb(74,190,245)">查看详情</a></span>';
                $content .= '</div>';
            }
            return json_encode(['status' => 200, 'data' => $content, 'offset' => $offset]);
        }
    }

    // 查看详情(我的借款)
    public function actionViewDetail()
    {
        if(Yii::$app->request->isAjax){
            $id = (int)$this->get('id');
            if(!$id){
                return json_encode(['status'=>100,'msg'=>'参数错误']);
            }
            $apply = Apply::find()->where(['id'=>$id])->asArray()->one();
            $apply['b_time'] = date('Y/m/d H:i:s',$apply['b_time']);
            $apply['e_time'] = date('Y/m/d H:i:s',$apply['e_time']);
            $status =  [1=>'待审核',2=>'审核通过',3=>'审核未通过',4=>'已放款',5=>'已还款'];
            $apply['status'] = $status[$apply['status']];
            return json_encode(['status'=>200,'data'=>$apply]);
        }
    }

    // 资金记录
    public function actionMoneyLog()
    {
        if(!Yii::$app->session['is_login']){
            return $this->redirect(['member/login']);
        }
        $member = Member::findOne(Yii::$app->session['id']);
        $money_logs = AdminMoneyLog::find()->where(['mem_id'=>Yii::$app->session['id']])->orderBy(['create_time'=>SORT_DESC])->limit($this->limit)->all();
        return $this->render('money-log',['member'=>$member,'money_logs'=>$money_logs]);
    }

    // 加载更多(资金记录)
    public function actionMoneyLogMore()
    {
        if(Yii::$app->request->isAjax){
            $offset = $this->get('offset');
            $money_logs = AdminMoneyLog::find()->where(['mem_id'=>Yii::$app->session['id']])->orderBy(['id'=>SORT_DESC])
                ->offset($offset)->limit($this->limit)->all();
            if(!$money_logs){
                return json_encode(['status'=>100,'msg'=>'没有更多数据']);
            }
            $offset = $offset + $this->limit;
            $content = '';
            foreach ($money_logs as $money_log){
               $content .= '<div class="record-item">';
                    $content .= '<span>'.date('Y/m/d',$money_log['create_time']).'</span>';
                    if($money_log['type'] == 1){
                        $content .= '<span style="color: green">+'.$money_log['money'].'</span>';
                    }else{
                        $content .= '<span style="color: red">-'.$money_log['money'].'</span>';
                    }
                    $content .= '<span>'.$money_log['balance'].'</span>';
                    $content .= '<span><a href="javascript:money_detail('.$money_log['id'].')" style="color: rgb(74, 190, 245)">查看详情</a></span>';
               $content .= '</div>';
            }
            return json_encode(['status'=>200,'data'=>$content,'offset'=>$offset]);
        }
    }

    // 资金详情
    public function actionMoneyDetail()
    {
        if(Yii::$app->request->isAjax) {
            $id = (int)$this->get('id');
            if (!$id) {
                return json_encode(['status' => 100, 'msg' => '参数错误']);
            }
            $money_log = AdminMoneyLog::find()->where(['id'=>$id])->asArray()->one();
            $money_log['create_time'] = date('Y/m/d H:i:s', $money_log['create_time']);
            if($money_log['way'] == 1){
                $money_log['way'] = '返佣';
            }else{
                $money_log['way'] = '提现';
            }
            return json_encode(['status'=>200,'data'=>$money_log]);
        }
    }
}