

<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
<!--                <div class="box-header with-border">-->
<!--                    <h3 class="box-title">主要</h3>-->
<!--                </div>-->
                    <!-- /.box-header -->
                <div class="box-body" style="font-size: 16px">
                    欢迎<?= $role['name']?>： <span class="text-red"><?= $user['uname']?></span>！&nbsp;&nbsp;&nbsp;&nbsp;当前时间：<span class="text-green" id="time"></span>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
	<!-- Small boxes (Stat box) -->
	<div class="row">
		<div class="col-md-12">
		<div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">系统信息</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
                <tr>
                  <th style="width: 10px">#</th>
                  <th style="width: 200px">名称</th>
                  <th>信息</th>
                </tr>
                <?php 
                    $count = 1;
                    foreach($sysInfo as $info){
    			       echo '<tr>';
    			       echo '  <td>'. $count .'</td>';
    			       echo '  <td>'.$info['name'].'</td>';
    			       echo '  <td>'.$info['value'].'</td>';
    			       echo '</tr>';
    			       $count++;
    			   }
    			   ?>
              </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              
            </div>
          </div>
          <!-- /.box -->
		</div>
	</div>
	<!-- /.row -->
	<!-- Main row -->
	<div class="row">
	</div>
	<!-- /.row (main row) -->
</section>
<!-- /.content -->

<!-- 页面时钟-->
<script type="text/javascript">

    $(function () {
       ShowTime();
    });

    // 不满10，补0
   function check(val) {
            if (val < 10) {
                return ("0" + val);
            } else {
                return (val);
            }
        }

    // 显示时间
    function ShowTime() {
        var date = new Date();
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var day = date.getDate();
        var hour = date.getHours();
        var minutes = date.getMinutes();
        var second = date.getSeconds();
        var timeStr = year + "-" + check(month) + "-" + check(day) + "&nbsp;&nbsp;" + check(hour)
            + ":" + check(minutes) + ":" + check(second);
        document.getElementById("time").innerHTML = timeStr;
        setTimeout('ShowTime()', 1000);
    }
</script>
