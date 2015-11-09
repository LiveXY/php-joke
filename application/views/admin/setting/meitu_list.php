
<script type="text/javascript" src="<?=RESOURCE?>js/fancybox/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="<?=RESOURCE?>js/jquery.uploadify.min.js"></script>
<link rel="stylesheet" href="<?=RESOURCE?>js/fancybox/jquery.fancybox.css" />

<style type="text/css" media="screen">
	.tag { display: block; margin:5px 5px; float:left; padding:5px 10px; border:1px solid #999; border-radius:25px; color:#000; }
	.tag:hover, .tag.active { border-color:#911; background-color:#911; color:#fff; }
	.msg_cm { padding-top: 20px; padding-bottom:20px; }
	.td_content { white-space:normal; overflow: normal; }
	.uploadImg-btn,.uploadify-button { background-color: #E7E7E7; border-radius:25px; }
	.photo { display:block; width:300px; height:300px; float:left; margin-bottom:10px; margin-right:10px; position:relative; }
	.photo img.p { width:300px; }
	.photo span { display: block; position:absolute; bottom:0px; width:300px; left:0px; height:40px; line-height:40px; color:#fff; text-align:center; }
	.photo span.title { top:0; bottom:none; background-color:rgba(0,0,0,.3); padding:0; }
	.photo .delete { display:block; float: right; height:40px; line-height:40px; margin-right:10px; cursor:pointer; color:#0066cc; }
	.photo .edit { display:block; float: left; height:40px; line-height:40px; margin-left:10px; cursor:pointer; color:#0066cc; }
	.content-operate .active { color:#f00; }
</style>
<div class="content-wrapper">
	<div class="content-operate">
        <span class="title"><img src="<?=RESOURCE?>images/ico/back.gif" class="ico" /> 美图管理：</span>
        <a href="?" class="<?=$tid == 0 ? 'active' : ''?>">全部</a>
        <?php foreach($tags as $t):?>
        	<?php if ($t->tid<30 || empty($t->title)) continue;?>
		<a href="?tag=<?=$t->tid?>" class="<?=$t->tid == $tid ? 'active' : ''?>"><?=$t->title?></a>
		<?php endforeach;?>
        <a class="btn updateMeitu" href="#"><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 上传美图</a>
    </div>
    <div class="content-list clearfix">
		<?php foreach($list as $info):?>
			<a class="photo fancybox" href="<?=RESOURCE?>upload/meitu/<?=$info->img?>">
				<span class="title"><?=$info->tags?></span>
				<img class="p" src="<?=RESOURCE?>upload/meitu/thumb-<?=$info->img?>" />
				<span>
					<label class="delete" jid="<?=$info->jid ?>" href="#"><img src="<?=RESOURCE?>images/ico/delete.gif" class="ico" /> 刪除</label>
					<label class="edit" jid="<?=$info->jid ?>" href="#"><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" /> 修改</label>
				</span>
			</a>
		<?php endforeach;?>
	</div>
	<div class="content-more" style="text-align:center">
		<?php if(count($list)==0):?>
			暂无数据！
		<?php else:?>
			<a href="#" class="showMore"><img src="<?=RESOURCE?>images/ico/desc.gif" class="ico" /> 查看更多</a>
		<?php endif?>
	</div>
</div>
<script type="text/javascript">
happi.pro.admin.pageLoad(true);
$(".updateMeitu").click(function(){
	var me = $(this);
	var html = '<table class="table-form"><tr>\
		<th class="t _w30 nofix">请选择多个标签：</th>\
		<td class="t pl5 td_content">\
<?php foreach($tags as $tag):?><?php if ($tag->tid<30 || empty($tag->title)) continue;?>
		<a href="#" class="tag" tid="<?=$tag->tid?>"><?=$tag->title?></a>\
<?php endforeach;?>
		</td>\
	</tr><tr>\
		<th class="_w30">请选择图片：</th>\
		<td class="pl5">\
			<input type="file" id="fileImg" style="display:none;" />\
			<textarea class="textarea hide" id="txtImg" name="txtImg"></textarea>\
		</td>\
	</tr></table>';
	$(window).window({
	    id: "table-form-confirm", title: "上传美图：", isDrag: false,
	    html: html, okTitle: "上传美图", width: 400, height: 'auto'
	});
	var fileCount = 0, files = [];
	var doneUpload = function() {
		if (fileCount == files.length) {
			var tags = '';
			$('.tag').each(function(){ if ($(this).hasClass('active')) tags += $(this).attr('tid') + ';'; });

			$.ajax({
				url: "<?=BASEURI?>admin/setting/check_meitu",
				data: { file: files, tags: tags },
				success: function (status) {
					if (status==0) return $(window).alert({text:'上传失败！'});
					else location.reload();
				}
			});
		}
	};
	$("#txtImg").uploadSwf({
		width: 150, height: 41, view: false,
		title: '上传美图文件',
		url: '<?=BASEURI ?>api/upload/meitu',
		multi: true, queueSizeLimit: 10,
		dialogClose: function(queueData){
			fileCount = queueData.filesQueued;
		}
	}, function(data){
		if (data.err.length > 0) { $(window).alert({text: data.err}); return false; }

		var file = data.msg.split('/');
		file = file[file.length - 1];
		files.push(file);
		doneUpload();
	});

	return false;
});
$(".tag").live('click', function(){
	if ($(this).hasClass('active')) $(this).removeClass('active'); else $(this).addClass('active');
	return false;
});
$('.edit').live('click', function(){
	var tags = '';
	var me = $(this);
	var html = '<table class="table-form"><tr>\
		<th class="t _w30 nofix">请选择多个标签：</th>\
		<td class="t pl5 td_content">\
<?php foreach($tags as $tag):?><?php if ($tag->tid<30 || empty($tag->title)) continue;?>
		<a href="#" class="tag" tid="<?=$tag->tid?>"><?=$tag->title?></a>\
<?php endforeach;?>
		</td>\
	</tr></table>';
	$(window).window({
	    id: "table-form-confirm", title: "修改标签：", isDrag: false,
	    html: html, okTitle: "修改标签", width: 400, height: 'auto',
	    okClick: function() {
			tags = '';
			$('.tag').each(function(){ if ($(this).hasClass('active')) tags += $(this).attr('tid') + ';'; });

			$.ajax({
				url: "<?=BASEURI?>admin/setting/meitu_edit",
				data: { jid: me.attr('jid'), tags: tags },
				success: function (status) {
					if (status==1) me.parent().prev().prev().text(tags); else $(window).alert({text:'修改失败！'});
				}
			});
	    }
	});
	$('.msg_cm').css('padding-top', '0px').css('padding-bottom', '0px');
	tags = me.parent().prev().prev().text().split(';');
	$(tags).each(function(){
		var tag = this;
		$('.tag').each(function(){ if ($(this).attr('tid') == tag) $(this).addClass('active'); });
	});

	return false;
});
$('.delete').live('click', function(){
	var me = $(this);
	if (!confirm('删除不可恢复，您确实要删除美图吗？')) return false;

	$.ajax({
		url: "<?=BASEURI?>admin/setting/meitu_delete",
		data: { jid: me.attr('jid') },
		success: function (status) {
			if (status==1) me.parent().parent().remove(); else $(window).alert({text:'删除失败！'});
		}
	});
	return false;
});
var page = 1;
$('.showMore').click(function(){
	page++;
	$.ajax({
		url: "<?=BASEURI?>admin/setting/meitu_more",
		data: { page: page, tid: '<?=$tid?>' },
		success: function (json) {
			if (json.status == 1 && json.list && json.list.length > 0) {
				$(json.list).each(function(){
					$('.content-list').append('<a class="photo fancybox" href="<?=RESOURCE?>upload/meitu/{0}">\
						<span class="title">{2}</span>\
						<img class="p" src="<?=RESOURCE?>upload/meitu/thumb-{0}" />\
						<span>\
							<label class="delete" jid="{1}" href="#"><img src="<?=RESOURCE?>images/ico/delete.gif" class="ico" /> 刪除</label>\
							<label class="edit" jid="{1}" href="#"><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" /> 修改</label>\
						</span>\
					</a>'.format(this.img, this.jid, this.tags));
				});
			} else $('.content-more').hide();
		}
	});
	return false;
});
$('.fancybox').fancybox();
</script>
