<script type="text/javascript" src="<?=BASEURI?>client/js/jquery.uploadify.min.js"></script>
<div class="content-wrapper">
	<div class="content-operate">
		<span class="title"><?php if ($id >= 0) { ?><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" />  修改<?php } else { ?><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加<?php } ?>笑话</span>
	</div>
	<div class="content-list">
		<?php if(($id <1 && $user_right['add']) || $user_right['edit']):?>
		<form method="post" name="data" action="<?=BASEURI?>admin/setting/audit_post?id=<?=$id ?>">
		<?php endif;?>
		<table class="table-form">
			<colgroup>
				<col width="<?=Util::isMobile() ? '50px':'150px'?>" />
				<col />
			</colgroup>
			<tr>
				<th class="t">标题：</th>
				<td class="pl5 t"><input type="text" class="input_text" name="txtTitle" value="<?= $info ? $info->title?:'' : "" ?>" /></td>
			</tr><tr>
				<th>内容：</th>
				<td class="pl5"><textarea class="textarea" name="txtJoke"><?= $info ? $info->joke ? str_replace('<br/>', "\n", $info->joke) :'' : "" ?></textarea></td>
			</tr><tr>
				<th>标签：</th>
				<td class="pl5">
					<textarea class="checkboxlist" id="txtTags" name="txtTags"></textarea>
				</td>
			</tr><tr>
				<th>评分：</th>
				<td class="pl5"><input type="text" class="input_text" name="txtScore" value="50" /></td>
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
var tags = [<?php foreach($tags as $t) { if ($t->tid>=30 || empty($t->title)) continue; ?>{id:"<?=$t->tid?>",name:"<?=$t->title?>"},<?php } ?>];
var defTags = [];
var txtTags = $('#txtTags').checkBoxList();
txtTags.loadJson(tags, defTags);

$("input[type='submit']").click(function(){
	if ($("input[name='txtTitle']").val() == '') { $(document).alert({text:"请输入标题！", cancelClick:function(){ $("input[name='txtTitle']").focus(); }}); return false; };
	if ($("input[name='txtJoke']").val() == '') { $(document).alert({text:"请输入内容！", cancelClick:function(){ $("input[name='txtJoke']").focus(); }}); return false; };
	return true;
});
</script>