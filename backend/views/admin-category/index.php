
<?php
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>

<?php $this->beginBlock('header');  ?>
<!-- <head></head>中代码块 -->

<link rel="stylesheet" href="<?=Url::base()?>/backend/web/plugins/jstree/dist/themes/default/style.min.css" />
<script src="<?=Url::base()?>/backend/web/plugins/jstree/dist/jstree.min.js"></script>

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
                <?php ActiveForm::begin([ 'method'=>'get','action'=>Url::toRoute(['admin-category/index']), 'options' => ['class' => 'form-inline']]); ?>
                  <div class="form-group" style="margin: 5px;">
                          <input type="text" class="form-control" name="query[name]"  value="<?= isset($query['name']) ? $query['name'] : ''?>" placeholder="分类名">
                  </div>
              <div class="form-group">
              	<input type="submit" class="btn btn-primary btn-sm" value="搜索" disabled>
           	  </div>
               <?php ActiveForm::end(); ?> 
            </div>
          	</div>
          	<!-- row end search -->
              <div class="row">
                  <div class="box-tools" style="padding: 10px 0">
                      <div class="input-group input-group-sm" style="padding-left: 20px">
                          <button id="delete_btn" type="button" class="btn btn-xs btn-danger">删&nbsp;&emsp;除</button> |
                          <button id="edit_btn" type="button" class="btn btn-xs btn-primary">修&nbsp;&emsp;改</button> |
                          <button id="create_btn" type="button" class="btn btn-xs btn-primary">添&nbsp;&emsp;加</button> |
                          <button id="open_btn" type="button" class="btn btn-xs btn-primary">展开全部</button> |
                          <button id="close_btn" type="button" class="btn btn-xs btn-primary">折叠全部</button>
                      </div>
                  </div>
              </div>

            <!--jsTree-->
              <div id="jstree_demo_div"></div>

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

<!-- 添加-->
<div id="create_content" style="margin-top: 30px;display: none">
    <div class="form-group">
        <label class="col-sm-2 control-label">分类名</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="create[name]" placeholder="必填">
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">级别</label>
        <div class="col-sm-10">
            <select name="create[level]"  class="form-control">
                <?php foreach($category as $val):?>
                    <option value="<?=$val['level']?>"><?=$val['name']?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<!--修改-->
<div id="edit_content" style="margin-top: 30px;display: none">
    <div class="form-group">
        <label class="col-sm-2 control-label">ID</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="edit[id]" placeholder="必填" disabled>
        </div>
        <div class="clearfix"></div>
    </div>
   <div class="form-group">
        <label class="col-sm-2 control-label">分类名</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="edit[name]" placeholder="必填">
        </div>
        <div class="clearfix"></div>
    </div>
</div>


<?php $this->beginBlock('footer');  ?>
<!-- <body></body>后代码块 -->
 <script>

     // 删除
 function deleteAction(id) {
     layer.confirm('确定要删除吗？',{},function () {
         $.get('<?= Url::toRoute(['admin-category/delete'])?>',{id:id},function (res) {
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

     //添加
     $('#create_btn').click(function(){
         layer.open({
             type: 1,
             title:'添加',
             area: ['450px', '240px'], //宽高
             btn:['确定'],
             content: $('#create_content'),
             yes:function(){
                 createAction();
             }
         });
     });

     function createAction(){
         var create_name = $('input[name="create[name]"]').val();
         var create_level = $('select[name="create[level]"]').val();
         if(create_name == ''){
             layer.msg('分类名不能为空',{icon:2,time:1500});
             return false;
         }
         $.get('<?= Url::toRoute(["admin-category/create"])?>',{name:create_name,level:create_level}, function (res) {
            if(res.status === 200){
                layer.msg(res.msg,{icon:1,time:1500},function(){
                    location.reload();
                })
            }else{
                layer.msg(res.msg);
            }
         },'json')
     }

     // jsTree
     $(function () { $('#jstree_demo_div').jstree({
         plugins:['types','themes','state','checkbox','search'],
         'core':{
             'data':<?= $categoryTree?>,
             'themes': {
                 "variant" : "large",
                 'stripes' : true,
                 "check_callback": true
             },
             'multiple': false,
             'state':true
         },
         'checkbox':{
             'undetermined':false,
             'three_state':false
         }
     });
     });

     $('#jstree_demo_div').on("changed.jstree", function (e, data) {
         window.id = data.selected[0] ;
     });

     // 查询节点名称
     var to = false;
     $("input[name='query[name]']").keyup(function(){
         if(to){
             clearTimeout(to);
         }
         to = setTimeout(function(){
             $('#jstree_demo_div').jstree(true).search($('input[name="query[name]"]').val()); //开启插件查询后 使用这个方法可模糊查询节点
         },250);
     });

     // 展开全部
     $('#open_btn').click(function () {
         $('#jstree_demo_div').jstree('open_all')
     });

     // 折叠全部
     $('#close_btn').click(function () {
         $('#jstree_demo_div').jstree('close_all')
     });

     // 删除
     $('#delete_btn').click(function() {
         if(!id){
             layer.msg('请选择要删除的分类~');
             return false;
         }
         layer.confirm('确定要删除吗？',{},function () {
             $.get('<?= Url::toRoute(['admin-category/delete'])?>',{id:id},function (res) {
                 if(res.status === 200){
                     layer.msg(res.msg,{icon:1,time:1500},function () {
                         window.location.reload();
                     })
                 }else{
                     layer.msg(res.msg,{icon:2,time:2000});
                 }
             },'json')
         })
     })

     // 修改
     $('#edit_btn').click(function() {
         if(!id){
             layer.msg('请选择要修改的分类~');
             return false;
         }
         var name = $('#'+id+'_anchor').text();
         $('#edit_content input[name="edit[id]"]').val(id);
         $('#edit_content input[name="edit[name]"]').val(name);
         layer.open({
             type: 1,
             title:'修改',
             area: ['450px', '240px'], //宽高
             btn:['确定'],
             content: $('#edit_content'),
             yes:function(){
                 updateAction();
             }
         });
     });

     function updateAction() {
         var id = $('#edit_content input[name="edit[id]"]').val();
         var name = $('#edit_content input[name="edit[name]"]').val();
         if(name === ''){
             layer.msg('分类名不能为空',{icon:2,time:1500});
             return false;
         }
         $.get('<?= Url::toRoute(['admin-category/update'])?>',{id:id,name:name},function (res) {
             if(res.status === 200){
                 layer.msg(res.msg,{icon:1,time:1500},function () {
                     window.location.reload();
                 })
             }else{
                 layer.msg(res.msg,{icon:2,time:2000});
             }
         },'json')
     }

     // slimScroll
     // $('#jstree_demo_div').slimScroll({height:'150px'});

</script>
<?php $this->endBlock(); ?>