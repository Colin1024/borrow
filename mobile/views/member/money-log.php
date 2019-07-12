<?php
use yii\helpers\Url;
?>

<?php $this->beginBlock('header')?>
<style>
    .promote span{font-size: 16px;color: rgb(255, 144, 0)}
    .promote span:nth-child(2){color: red}
    .promote-way .promote-link i{color: rgb(255, 144, 0)}
    .link-input input{width: 90%;height: 30px;border: 0;border-bottom: 1px solid rgb(243, 243, 243);}
    .link-input span{border: 1px solid rgb(255, 144, 0);width: 80%;text-align: center;margin: 15px 0;padding: 6px 0;background-color: rgb(255, 144, 0);color:#fff;border-radius: 3px}
    .link-qrcode img{width: 200px;height: 200px;}
    .promote-record{background-color: #fff;margin-top: 10px;border-radius: 3px;padding: 10px 20px;font-size: 14px}
    .record-header{display: flex;justify-content: space-between;}
    .record-item{display: flex;justify-content: space-between;align-items: center;padding: 10px 0;border-bottom: 1px solid rgb(243, 243, 243)}
    .record-item span{text-align: center;display: inline-block;width: 33%;line-height: 20px}
    .more{background-color: #fff;text-align: center;font-size: 16px;padding: 10px;border-radius: 1px;margin-top: 6px}
</style>
<?php $this->endBlock()?>

<div class="content_w">
    <div class="header" style="background-color: rgb(255, 144, 0)">
        <a href="<?= Url::toRoute(['member/index'])?>" class="back" style="display: block;width: 24px"><i class="fa fa-angle-left " style="font-size: 25px;padding: 5px"></i></a>
        <p>资金记录</p>
    </div>

    <div class="main">
        <div class="promote-record">
            <div class="record-header">
                <span style="line-height: 18px"><i class="fa fa-align-justify" style="color: rgb(255, 144, 0)"></i> 资金记录</span>
            </div>
        </div>
        <div class="promote-record data-list">
            <div class="record-item" style="color:  rgb(255, 144, 0)">
                <span>时间</span>
                <span>金额(元)</span>
                <span>账户余额(元)</span>
                <span>操作</span>
            </div>
                <?php foreach ($money_logs as $money_log):?>
                <div class="record-item">
                        <span><?= date('Y/m/d',$money_log['create_time'])?></span>
                        <?php if($money_log['type']==1):?>
                            <span style="color:green">+<?= $money_log['money']?></span>
                        <?php else:?>
                            <span style="color:red">-<?= $money_log['money']?></span>
                        <?php endif;?>
                        <span><?= $money_log['balance']?></span>
                        <span><a href="javascript:money_detail('<?= $money_log['id']?>')" style="color:rgb(74, 190, 245)">查看详情</a></span>
                </div>
                <?php endforeach;?>
        </div>
        <div class="more">加载更多</div>
    </div>
</div>

<?php $this->beginBlock('footer');?>
<script>
    // 查看详情
    function money_detail(id) {
         if(!id){
             return false;
         }
         $.get('<?= Url::toRoute(['member/money-detail'])?>',{id:id},function (res) {
             if(res.status === 200){
                 console.log((res.data));
                 var content = '';
                 content += '<table style="width: 100%">';
                 content += '<tr><td style="color: rgb(255, 144, 0)">时间：</td><td>'+res.data.create_time+'</td><tr>';
                 if(res.data.type == 1){
                     content += '<tr><td style="color: rgb(255, 144, 0)">金额：</td><td style="color:green">+'+res.data.money+' 元</td><tr>';
                 }else{
                     content += '<tr><td style="color: rgb(255, 144, 0)">金额：</td><td style="color:red">-'+res.data.money+' 元</td><tr>';
                 }
                 content += '<tr><td style="color: rgb(255, 144, 0)">类型：</td><td>'+res.data.way+'</td><tr>';
                 content += '<tr><td style="color: rgb(255, 144, 0)">备注：</td><td>'+res.data.remark+'</td><tr>';
                 content += '<tr><td style="color: rgb(255, 144, 0)">账户余额：</td><td>'+res.data.balance+'</td><tr>';
                 content += '</table>';
                 layer.open({
                     content:content,
                     btn:'关闭'
                 })
             }else{
                 layer.open({content:res.msg,skin:'msg',time:2});
             }
         },'json')
    }

    // 加载更多
    var offset = 5;
    $('.more').click(function () {
        $('.more').html('加载中 <i class="fa fa-spinner fa-spin"></i>');
        $.get('<?= Url::toRoute(['member/money-log-more'])?>',{offset:offset},function (res) {
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
