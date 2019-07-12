
<?php
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>

<?php $this->beginBlock('header');  ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">数据列表</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <!-- row start search-->
                        <div class="row">
                            <div class="col-sm-12">
                                <?php ActiveForm::begin([ 'method'=>'get','action'=>Url::toRoute(['admin-money-log/index']), 'options' => ['class' => 'form-inline']]); ?>
                                <div class="form-group" style="margin: 5px;">
                                    <input type="text" class="form-control" name="query[name]"  placeholder="用户姓名/手机号" value="<?= isset($query['name']) ? $query['name'] : ''?>">
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <select class="form-control" name="query[way]">
                                        <option value="">方式</option>
                                        <option value="1" <?= isset($query['way']) && $query['way'] == 1 ? 'selected' : '' ?> >返佣</option>
                                        <option value="2" <?= isset($query['way']) && $query['way'] == 2 ? 'selected' : '' ?> >提现</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <select class="form-control" name="query[type]">
                                        <option value="">类型</option>
                                        <option value="1" <?= isset($query['type']) && $query['type'] == 1 ? 'selected' : '' ?> >增加</option>
                                        <option value="2" <?= isset($query['type']) && $query['type'] == 2 ? 'selected' : '' ?> >减少</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <input type="text" id="b_time" class="form-control" name="query[b_time]"  value="<?= isset($query['b_time']) ? $query['b_time'] : ''?>" placeholder="开始时间"  >
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <input type="text" id="e_time" class="form-control" name="query[e_time]"  value="<?= isset($query['e_time']) ? $query['e_time'] : ''?>" placeholder="结束时间">
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary btn-sm" value="搜索">
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                        <!-- row end search -->
                        <div class="row">
                            <div class="box-tools" style="padding: 10px 0">
                                <div class="input-group input-group-sm" style="padding-left: 20px">
                                    <button id="delete_btn" type="button" class="btn btn-xs btn-danger">批量删除</button> |
                                    <a href="<?= Url::toRoute(['admin-money-log/export','query'=>$query])?>"><button id="export_btn" type="button" class="btn btn-xs btn-primary">导&nbsp;&emsp;出</button></a>
                                </div>
                            </div>
                        </div>
                        <!-- row start -->
                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                <table id="data_table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="data_table_info">
                                    <thead class="text-nowrap">
                                    <tr role="row">
                                        <th><input id="data_table_check" type="checkbox"></th>
                                        <th>ID</th>
                                        <th>用户名</th>
                                        <th>金额</th>
                                        <th>方式</th>
                                        <th>类型</th>
                                        <th>账户余额</th>
                                        <th>备注</th>
                                        <th>创建时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($models as $model):?>
                                        <tr>
                                            <td><label><input type="checkbox" value="<?= $model['id']?>"></label></td>
                                            <td><?= $model['id']?></td>
                                            <td><?= $model['member'] ? $model['member']['name'].' | '.$model['member']['tel'] : ''?></td>
                                            <td><?= $model['money']?></td>
                                            <td><span class="label <?= $model['way']==1 ? 'bg-green' : 'bg-red'?>"><?= $model['way']==1 ? '返佣' : '提现'?></span></td>
                                            <td><span class="label <?= $model['type']==1 ? 'bg-green' : 'bg-red'?>"><?= $model['type']==1 ? '增加' : '减少'?></span></td>
                                            <td><?= $model['balance']?></td>
                                            <td><?= $model['remark']?></td>
                                            <td><?= date('Y-m-d H:i:s',$model['create_time'])?></td>
                                            <td>
                                                <a  class="btn btn-danger btn-sm" onclick="deleteAction('<?= $model['id']?>')" href="javascript:;"> <i class="glyphicon glyphicon-trash icon-white"></i> 删除</a>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                    <!-- <tfoot></tfoot> -->
                                </table>
                            </div>
                        </div>
                        <!-- row end -->

                        <!-- row start -->
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info" id="data_table_info" role="status" aria-live="polite">
                                    <div class="infos">
                                        从<?= $pages->getPage() * $pages->getPageSize() + 1 ?>
                                        到 <?= ($pageCount = ($pages->getPage() + 1) * $pages->getPageSize()) < $pages->totalCount ?  $pageCount : $pages->totalCount?>
                                        共 <?= $pages->totalCount?> 条记录</div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="data_table_paginate">
                                    <?= LinkPager::widget([
                                        'pagination' => $pages,
                                        'nextPageLabel' => '»',
                                        'prevPageLabel' => '«',
                                        'firstPageLabel' => '首页',
                                        'lastPageLabel' => '尾页',
                                    ]); ?>
                                </div>
                            </div>
                        </div>
                        <!-- row end -->
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

<?php $this->beginBlock('footer');  ?>
<!-- <body></body>后代码块 -->
<script>
    // 时间选择器
    $('#b_time').datepicker();
    $('#e_time').datepicker();

    // 删除
    function deleteAction(id) {
        layer.confirm('确定要删除吗？',{},function () {
            $.get('<?= Url::toRoute(['admin-money-log/delete'])?>',{id:id},function (res) {
                if(res.status === 200){
                    layer.msg(res.msg,{icon:1,time:1500},function () {
                        window.location.reload();
                    })
                }else{
                    layer.msg(res.msg,{icon:2,time:2000});
                }
            },'json')
        })
    }

    //批量删除
    $('#delete_btn').click(function() {
        var ids = '';
        if ($('tbody input:checked').length === 0) {
            layer.msg('请选择你要删除的记录');
            return false;
        } else {
            layer.confirm('确定要删除吗？', {}, function () {
                $('tbody input:checked').each(function (index) {
                    ids += ($(this).val() + ',');
                });
                var csrfToken = $('meta[name="csrfToken"]').attr('content');
                $.post('<?=Url::toRoute(['admin-money-log/batch-del'])?>', {ids: ids, _csrf: csrfToken}, function (res) {
                    if (res.status === 200) {
                        layer.msg(res.msg, {icon: 1, time: 1500}, function () {
                            location.reload();
                        })
                    } else {
                        layer.msg(res.msg, {icon: 2, time: 2000});
                    }
                }, 'json')
            })
        }
    });

</script>
<?php $this->endBlock(); ?>