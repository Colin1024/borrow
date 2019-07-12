<?php
use yii\helpers\Url;
?>

<?php $this->beginBlock('header')?>
<style>
    .bill{background-color: #fff;margin-top: 10px;border-radius: 3px;padding: 10px 20px;display: flex;justify-content: space-between}
    .bill span{font-size: 16px;color: rgb(74, 190, 246)}
    .bill span:nth-child(2){color: red}
    .withdraw-box{background-color: #fff;margin-top: 10px;border-radius: 3px;padding: 20px}
    .item{text-align: center;font-size: 25px;display: flex;justify-content: space-between;padding-bottom: 10px;border-bottom: 1px solid rgb(243, 243, 243)}
    .tip{padding-bottom: 15px;padding-left: 5px;font-size: 14px}
    .item .icon{width: 10%;color:rgb(255, 144, 0)}
    .item input{width: 85%;height: 30px;font-size: 16px;border: 0;}
    .btn{display: flex;justify-content: center;margin: 15px;}
    .btn button{border: 0;background-color: rgb(255, 144, 0);width: 95%;color: #ffff;font-size: 17px;border-radius: 3px;padding: 5px}
    .unit{font-size: 16px;line-height: 30px}
    .withdraw-record{background-color: #fff;margin-top: 10px;border-radius: 3px;padding: 10px 20px;font-size: 14px}
    .record-header{display: flex;justify-content: space-between;}
    .record-item{display: flex;justify-content: space-between;align-items: center;padding: 10px 0;border-bottom: 1px solid rgb(243, 243, 243)}
    .record-item span{text-align: center;display: inline-block;width: 33%;line-height: 20px}
    .withdraw-button{border: 1px solid rgb(255, 144, 0);color: rgb(255, 144, 0);padding: 2px 4px;border-radius: 3px}
    .more{background-color: #fff;text-align: center;font-size: 16px;padding: 10px;border-radius: 1px;margin-top: 6px}
</style>
<?php $this->endBlock()?>

<div class="content_w">

    <div class="header" style="background-color: rgb(255, 144, 0)">
        <a href="<?= Url::toRoute(['member/index'])?>" class="back" style="display: block;width: 24px"><i class="fa fa-angle-left " style="font-size: 25px;padding: 5px"></i></a>
        <p>我的资金</p>
    </div>

    <div class="main">
       <div class="bill">
           <span><i class="fa fa-database"></i> 余额</span>
<!--           <i class="iconfont icon-smile" style="color: red;font-size: 25px"></i>-->
           <span class="bill-money"><?= $member['balance']?> 元</span>
       </div>

        <div class="withdraw-box" style="display: none">
            <div class="tip">
                <p style="color: red">* 最小提现金额：<i style="font-weight: bolder"><?= $min_withdraw_money?></i> 元</p>
            </div>
            <form action="" method="post" onsubmit="return false;">
                <div class="item">
                    <span class="icon"><i class="fa fa-cny"></i></span>
                    <input type="number" name="money" placeholder="请输入提现金额">
                    <span class="unit">元</span>
                </div>
                <div class="btn">
                    <button type="submit">申请提现</button>
                </div>
            </form>
        </div>

        <div class="withdraw-record">
            <div class="record-header">
                <span style="line-height: 18px"><i class="fa fa-align-justify" style="color: rgb(255, 144, 0)"></i> 提现记录</span>
                <span class="withdraw-button">提现</span>
            </div>
        </div>

        <div class="withdraw-record data-list">
            <div class="record-item" style="color:  rgb(255, 144, 0)">
                <span>申请时间</span>
                <span>金额(元)</span>
                <span>状态</span>
            </div>
            <?php foreach ($withdraws as $withdraw):?>
            <div class="record-item">
                <span><?= date('Y/m/d',$withdraw['create_time'])?></span>
                <span><?= $withdraw['money']?></span>
                <span style="color: red"><?php $status = [1=>'审核中',2=>'通过',3=>'未通过',4=>'成功']; echo $status[$withdraw['status']]?></span>
            </div>
            <?php endforeach;?>
        </div>
        <div class="more">加载更多</div>
    </div>
</div>

<?php $this->beginBlock('footer');?>
<script>
    // 提现框显示/隐藏
    $('.withdraw-button').click(function () {
        $('.withdraw-box').slideToggle();
    });

    // 提交
    $('.btn').click(function () {
        var money = $('input[name="money"]').val();
        var balance = <?= $member['balance']?>;
        var _csrf = '<?= Yii::$app->request->csrfToken?>';
        var min_withdraw_money = <?= $min_withdraw_money?>;
        if(money === ''){
            layer.open({content:'金额不能为空',skin:'msg',time:2});
            return false;
        }
        if(!/^\d+[.]?\d*$/.test(money)){
            layer.open({content:'金额格式不正确',skin:'msg',time:2});
            return false;
        }
        if(money <= 0){
            layer.open({content:'金额必须大于0',skin:'msg',time:2});
            return false;
        }
        if(money < min_withdraw_money){
            layer.open({content:'金额不能小于最小提现金额',skin:'msg',time:2});
            return false;
        }
        if(money > balance){
            layer.open({content:'提现金额不能大于账户余额',skin:'msg',time:2});
            return false;
        }
        $.post('<?= Url::toRoute(['member/balance'])?>',{_csrf:_csrf,money:money},function (res) {
            if(res.status === 200){
                layer.open({content:'<span style="color:green;font-size: 18px">申请成功</span>,需要等待管理员审核。审核不通过，会返还扣除的资金。你可以通过下方提现记录表，随时查看审核状态',btn:'我知道了',yes:function () {
                        location.reload();
                    }})
            }else{
                layer.open({content:res.msg,skin:'msg',time:2});
            }
        },'json')
    })

    // 加载更多
    var offset = 5;
    $('.more').click(function () {
        $('.more').html('加载中 <i class="fa fa-spinner fa-spin"></i>');
        $.get('<?= Url::toRoute(['member/balance-more'])?>',{offset:offset},function (res) {
            console.log(res);
            if(res.status === 200){
                $('.data-list').append(res.data);
                offset = res.offset;
                $('.more').html('加载更多');
            }else{
                $('.more').html('没有更多数据');
            }
        },'json')
    })


</script>
<?php $this->endBlock('footer');?>
