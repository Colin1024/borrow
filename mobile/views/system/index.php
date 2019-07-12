<?php
use yii\helpers\Url;
?>

<?php $this->beginBlock('header')?>
<style>
    .consult{background-color: #fff;display: flex;justify-content: space-between;padding: 10px}
    .consult img{width: 45px;}
    .consult p{margin: 20px auto 6px;font-size: 16px;color: rgb(143, 143, 148)}
    .consult-item{text-align: center;width: 50%}
    .consult-time{background-color: #fff;margin-top: 8px;padding: 10px 15px}
    .consult-time p{font-size: 16px;}
    .tip{margin-top: 50px;padding: 10px 15px;font-size: 18px;color:rgb(243, 38, 197);text-align: center;}
</style>
<?php $this->endBlock()?>

<div class="content_w">

    <div class="header" style="background-color: rgb(255, 144, 0)">
        <!--        <a href="javascript:history.back();" class="back" style="display: block;width: 24px"><i class="fa fa-angle-left " style="font-size: 25px;padding: 5px"></i></a>-->
        <p>客服</p>
    </div>

    <div class="main">
        <div class="consult">
            <div class="consult-item" style="border-right: 1px solid rgb(243, 243, 243)">
                <div><a href="http://wpa.b.qq.com/cgi/wpa.php?ln=2&uin=<?= $qq?>"><img src="<?= Url::base()?>/mobile/web/images/help_qq2.png" alt=""></a></div>
                <p><?= $qq?></p>
            </div>
            <div class="consult-item">
                <div><a href="wtai://wp//mc;<?= $phone?>"><img src="<?= Url::base()?>/mobile/web/images/help_tel2.png" alt=""></a></div>
                <p><?= $phone?></p>
            </div>
        </div>
        <div class="consult-time">
            <p style="color: rgb(38, 156, 229)"><i class="fa fa-qq"></i>&nbsp;&nbsp;QQ咨询：9:00 - 21:00</p>
        </div>
        <div class="consult-time">
            <p style="color: rgb(251, 111, 0)"><i class="fa fa-phone"></i>&nbsp;&nbsp;电话咨询：9:00 - 18:00</p>
        </div>
    </div>

    <!--背景音乐-->
    <!--    <embed src="--><?//= Url::base()?><!--/mobile/web/music/banhusha.mp3" hidden="true" loop="true">-->

    <div class="tip animated bounce">静等3秒，有彩蛋哦...</div>
</div>



<!--footer-->
<div class="footer">
    <ul>
        <li>
            <a href="<?= Url::toRoute(['index/index'])?>">
                <i class="iconfont icon-lobby"></i>
                <p>首页</p>
            </a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['product/index'])?>">
                <i class="iconfont icon-jiaoyiguanli-fenxiao"></i>
                <p>全部产品</p>
            </a>
        </li>
        <li>
            <a href="<?= Url::toRoute(['member/index'])?>">
                <i class="iconfont icon-geren"></i>
                <p>个人中心</p>
            </a>
        </li>
        <li  class="on">
            <a href="<?= Url::toRoute(['system/index'])?>">
                <i class="iconfont icon-kefu"></i>
                <p>客服</p>
            </a>
        </li>
    </ul>
</div>
<!--footer main-->

<?php $this->beginBlock('footer');?>
<script>
    // 微信分享
    var shares=null;
    var sweixin=null;
    var buttons=[
        {title:'我的好友',extra:{scene:'WXSceneSession'}},
        {title:'朋友圈',extra:{scene:'WXSceneTimeline'}},
        {title:'我的收藏',extra:{scene:'WXSceneFavorite'}}
    ];

    // 更新分享服务
    function updateServices(){
        plus.share.getServices(function(s){
            shares={};
            for(var i in s){
                var t=s[i];
                shares[t.id]=t;
            }
            sweixin=shares['weixin'];
        }, function(e){
            console.log('获取分享服务列表失败：'+e.message);
        });
    }

    if (window.plus) {
        plusReady()
    } else {
        document.addEventListener('plusready', plusReady, false)
    }

    function plusReady() {
        // updateServices();
        $('.tip').click(function () {
            // systemShare() //系统分享
            // find();
            // add();
            // shareWeb();
            // getGeocode();
            // pickFile();
            createDownload();
        });
    }

    // 通过定位模块获取位置信息
    function getGeocode(){
        console.log( "获取定位位置信息:" );
        plus.geolocation.getCurrentPosition( geoInf, function ( e ) {
            console.log( "获取定位位置信息失败："+e.message );
        },{geocode:true});
    }
    function geoInf( position ) {
        var str = "";
        str += "地址："+position.addresses+"\n";//获取地址信息
        str += "坐标类型："+position.coordsType+"\n";
        var timeflag = position.timestamp;//获取到地理位置信息的时间戳；一个毫秒数；
        str += "时间戳："+timeflag+"\n";
        var codns = position.coords;//获取地理坐标信息；
        var lat = codns.latitude;//获取到当前位置的纬度；
        str += "纬度："+lat+"\n";
        var longt = codns.longitude;//获取到当前位置的经度
        str += "经度："+longt+"\n";
        var alt = codns.altitude;//获取到当前位置的海拔信息；
        str += "海拔："+alt+"\n";
        var accu = codns.accuracy;//地理坐标信息精确度信息；
        str += "精确度："+accu+"\n";
        var altAcc = codns.altitudeAccuracy;//获取海拔信息的精确度；
        str += "海拔精确度："+altAcc+"\n";
        var head = codns.heading;//获取设备的移动方向；
        str += "移动方向："+head+"\n";
        var sped = codns.speed;//获取设备的移动速度；
        str += "移动速度："+sped;
        console.log(JSON.stringify(position));
        alert( str );
    }

    // 分享网页
    function shareWeb() {
        var msg = {type: 'web', thumbs: ['http://www.2345.com/i/search190510/idx-1.png']};
        msg.href = 'http://h5.hicolin.cn/';
        msg.title = '微信分享';
        msg.content = '微信分享描述';
        if (sweixin) {
            plus.nativeUI.actionSheet({title: '分享网页到微信', cancel: '取消', buttons: buttons}, function (e) {
                if (e.index > 0) {
                    share(sweixin, msg, buttons[e.index - 1]);
                }
            })
        } else {
            plus.nativeUI.alert('当前环境不支持微信分享操作!');
        }
    }

    // 分享
    function share(srv, msg, button) {
        console.log('分享操作：');
        if (!srv) {
            console.log('无效的分享服务！');
            return;
        }
        button && (msg.extra = button.extra);
        // 发送分享
        if (srv.authenticated) {
            console.log('---已授权---');
            doShare(srv, msg);
        } else {
            console.log('---未授权---');
            srv.authorize(function () {
                doShare(srv, msg);
            }, function (e) {
                console.log('认证授权失败：' + JSON.stringify(e));
            });
        }
    }

    // 发送分享
    function doShare(srv, msg){
        console.log(JSON.stringify(msg));
        srv.send(msg, function(){
            console.log('分享到"'+srv.description+'"成功！');
        }, function(e){
            console.log('分享到"'+srv.description+'"失败: '+JSON.stringify(e));
        });
    }



    // 获取通讯录联系人
    function find(){
        plus.contacts.getAddressBook(plus.contacts.ADDRESSBOOK_PHONE, function (addressbook) {
            addressbook.find(["displayName","phoneNumbers"],function(contacts){
                var list="";
                var num = 0;
                for(var i=0;i<contacts.length;i++){
                    if(contacts[i].phoneNumbers.length > 0){
                        num++;
                        var str=contacts[i].displayName.substr(contacts[i].displayName.length -2, 2);
                        list+="<div class='list-h'><h3>"+contacts[i].displayName+"("+contacts[i].phoneNumbers[0].value+")"+"</h3></div>";
                    }
                }
                // alert(num);
                document.write(list);
            }, function () {
                alert("error");
            },{multiple:true});
        },function(e){
            alert("Get address book failed: " + e.message);
        });
    }

    // 通讯录添加联系人
    function add() {
        plus.contacts.getAddressBook( plus.contacts.ADDRESSBOOK_PHONE, function( addressbook ) {
            // 可通过addressbook进行通讯录操作
            var contact = addressbook.create();
            contact.name = {givenName:"王安"};
            contact.phoneNumbers = [{type:"手机",value:"88888888",preferred:true}];
            contact.save( function () {
                alert( "保存联系人成功" );
            }, function ( e ) {
                alert( "保存联系人失败：" + e.message );
            } );
        }, function ( e ) {
            alert( "Get address book failed: " + e.message );
        } );
    }

    // 打开本地文件保存到相册
    function pickFile(){
        plus.gallery.pick(
            function(path){
                plus.gallery.save( path, function(){
                    plus.nativeUI.toast('ok')
                }, function(){
                    console.log('失败');
                });
            },
            function(e){alert('取消了选择');}
        );
    }

    // 下载图片到相册
    function createDownload() {
        var dtask = plus.downloader.createDownload( "https://www.2345.com/images/logo/logo_normal_20181008.png", {}, function ( d, status ) {
            // 下载完成
            if ( status == 200 ) {
                // plus.nativeUI.toast( "Download success: " + d.filename );
                plus.gallery.save( d.filename, function(){
                    plus.nativeUI.toast('ok')
                }, function(){
                    console.log('失败');
                });
            } else {
                plus.nativeUI.toast( "Download failed: " + status );
            }
        });
        //dtask.addEventListener( "statechanged", onStateChanged, false );
        dtask.start();
    }

    // 获取webview
    function getWebview() {
        var wvs=plus.webview.all();
        for(var i=0;i<wvs.length;i++){
            console.log('webview'+i+': '+wvs[i].getURL());
        }
    }

    // 系统分享
    function systemShare() {
        plus.share.sendWithSystem({content:'分享内容',href:'http://www.dcloud.io/'}, function(){
            console.log('分享成功');
        }, function(e){
            console.log('分享失败：'+JSON.stringify(e));
        });
    }



</script>
<?php $this->endBlock('footer');?>
