<?php
use yii\helpers\Url;
?>

<?php $this->beginBlock('header')?>
<style>
    .promote{background-color: #fff;margin-top: 10px;border-radius: 3px;padding: 10px 20px;display: flex;justify-content: space-between}
    .promote span{font-size: 16px;color: rgb(181, 144, 247)}
    .promote span:nth-child(2){color: red}
    .promote-way{background-color: #fff;margin-top: 10px;border-radius: 3px;padding: 10px 20px;font-size: 14px}
    .promote-way .promote-items{display: flex;justify-content: space-between;border-bottom: 1px solid rgb(243, 243, 243);padding: 10px 0}
    .promote-way .promote-link i{color: rgb(255, 144, 0)}
    .promote-way .promote-look{color: rgb(255, 144, 0);cursor: pointer}
    .link-input{display: flex;flex-direction: column;align-items: center;padding: 10px 0}
    .link-input input{width: 90%;height: 30px;border: 0;border-bottom: 1px solid rgb(243, 243, 243);}
    .link-input span{border: 1px solid rgb(255, 144, 0);width: 80%;text-align: center;margin: 15px 0;padding: 6px 0;background-color: rgb(255, 144, 0);color:#fff;border-radius: 3px}
    .link-qrcode{text-align: center}
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
        <p>我的推广</p>
    </div>

    <div class="main">
        <div class="promote">
            <span><i class="fa fa-fw fa-pie-chart"></i> 推广人数</span>
            <span class="promote-num"><?= $invite_num?> 个</span>
        </div>
        <div class="promote-way">
            <div class="promote-items">
                <span class="promote-link"><i class="fa fa-fw fa-chain"></i> 推广链接</span>
                <span class="promote-look" id="link-look">查看</span>
            </div>
            <div class="link-input" style="display: none">
                <input type="text" value="<?= $invite_url?>" disabled id="invite_url">
                <span onclick="copy('invite_url')">复制</span>
            </div>
            <div class="promote-items">
                <span class="promote-link"><i class="fa fa-fw fa-qrcode"></i> 推广二维码</span>
                <span class="promote-look"  id="qrcode-look">查看</span>
            </div>
            <div class="link-qrcode" style="display: none">
                <img src="<?= $invite_code?>" alt="" >
            </div>
        </div>

        <div class="promote-record">
            <div class="record-header">
                <span style="line-height: 18px"><i class="fa fa-align-justify" style="color: rgb(255, 144, 0)"></i> 推广记录</span>
            </div>
        </div>
        <div class="promote-record data-list">
            <div class="record-item" style="color:  rgb(255, 144, 0)">
                <span>时间</span>
                <span>用户名</span>
            </div>
            <?php foreach ($invites as $invite):?>
            <div class="record-item">
                <span><?= date('Y/m/d',$invite['create_time'])?></span>
                <span><?= $invite['tel']?></span>
            </div>
            <?php endforeach;?>
        </div>
        <div class="more">加载更多</div>
    </div>

</div>

<?php $this->beginBlock('footer');?>
<script>
    // 链接查看
    $('#link-look').click(function () {
        $('.link-input').slideToggle()
    });

    // 二维码查看
    $('#qrcode-look').click(function () {
        $('.link-qrcode').slideToggle()
    });

    // 复制链接
    function copy(id) {
        const range = document.createRange();
        range.selectNode(document.getElementById(id));
        const selection = window.getSelection();
        if(selection.rangeCount > 0){
            selection.removeAllRanges();
        }
        selection.addRange(range);
        document.execCommand('copy');
        layer.open({content:'复制成功',style: 'border:none; background-color:#78BA32; color:#fff;',skin:'msg',time:2})
    }

    // 加载更多
    var offset = 5;
    $('.more').click(function () {
        $('.more').html('加载中 <i class="fa fa-spinner fa-spin"></i>');
        $.get('<?= Url::toRoute(['member/invite-more'])?>',{offset:offset},function (res) {
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
