<script type="text/javascript" src="<?=BASEURI?>client/js/jquery.uploadify.min.js"></script>
<div class="content-wrapper">
	<div class="content-operate">
		<span class="title"><?php if ($id >= 0) { ?><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" />  修改<?php } else { ?><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加<?php } ?>版本</span>
	</div>
	<div class="content-list">
		<?php if(($id <1 && $user_right['add']) || $user_right['edit']):?>
		<form method="post" name="data" action="<?=BASEURI?>admin/sys/version_post?id=<?=$id ?>">
		<?php endif;?>
		<table class="table-form">
			<colgroup>
				<col width="<?=Util::isMobile() ? '50px':'150px'?>" />
				<col />
			</colgroup>
			<tr>
				<th class="t">版本号：</th>
				<td class="pl5 t"><input type="text" class="input_text" name="txtVID" value="<?= $info ? $info->vid : "" ?>"/> 如:300</td>
			</tr><tr>
				<th>版本名称：</th>
				<td class="pl5"><input type="text" class="input_text" name="txtVName" value="<?= $info ? $info->vname?:'' : "" ?>" /> 如:v3.0.0</td>
			</tr><tr>
				<th>升级内容：</th>
				<td class="pl5"><textarea class="textarea" name="txtVText"><?= $info ? $info->vtext?:'' : "" ?></textarea></td>
			</tr><tr>
				<th>上传文件：</th>
				<td class="pl5">
					<span id="fileExists" class="<?=$fileExists?'':'hide'?>">已上传</span>
					<input type="file" id="fileImg" style="display:none;" />
					<textarea class="textarea hide" id="txtImg" name="txtImg"></textarea>
				</td>
			</tr><tr>
				<th>状态：</th>
				<td class="pl5"><div class="input_cbo"><label>请选择状态</label>
					<select name="txtStatus">
						<option value="0" <?= ($info && $info->status==0) ? "selected=selected" : "" ?>>不可用</option>
						<option value="1" <?= ($info && $info->status==1) ? "selected=selected" : "" ?>>可用</option>
					</select>
				</div></td>
			</tr><tr>
				<td></td>
				<td class="pl5">
					<?php if(($id <1 && $user_right['add']) || $user_right['edit']):?>
					<input type="submit" class="input_button4" value=" 提交数据 " />　
					<?php endif;?>
				</td>
			</tr>
		</table>
		<?php if(($id <1 && $user_right['add']) || $user_right['edit']):?>
		</form>
		<?php endif;?>
	</div>
</div>

<script type="text/javascript">
happi.pro.admin.pageLoad();
function getUrl(){ return '<?=BASEURI?>api/upload/zip?v=' + $("input[name='txtVName']").val(); }
$("#fileImg").uploadImg({
	width: 150, height: 21,
	title: '<img src="/client/images/ico/up.gif" /> 上传zip更新包',
	url: getUrl()
}, function(file, data, response){
	data = JSON.parse(data);
	if (data.err.length > 0) { alert(data.err); return false; }
	else $('#fileExists').removeClass('hide');
}, function(options){
	options.formData = { };
});

$("input[type='submit']").click(function(){
	if ($("input[name='txtVID']").val() == '') { $(document).alert({text:"请输入版本号！", cancelClick:function(){ $("input[name='txtVID']").focus(); }}); return false; };
	if ($("input[name='txtVName']").val() == '') { $(document).alert({text:"请输入版本名称！", cancelClick:function(){ $("input[name='txtVName']").focus(); }}); return false; };
	if ($("input[name='txtVText']").val() == '') { $(document).alert({text:"请输入升级内容！", cancelClick:function(){ $("input[name='txtVText']").focus(); }}); return false; };
	return true;
});
if (<?=$post?>) {
	top.tab.refresh('VersionManage', true);
	$(document).alert({text:"修改成功！"});
}
</script>