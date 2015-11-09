<div class="content-wrapper">
	<div class="content-operate">
		<span class="title"><?php if ($id >= 0) { ?><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" />  修改<?php } else { ?><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加<?php } ?>标签</span>
	</div>
	<div class="content-list">
		<?php if(($id <1 && $user_right['add']) || $user_right['edit']):?>
		<form method="post" name="data" action="<?=BASEURI?>admin/setting/tag_post?id=<?=$id ?>">
		<?php endif;?>
		<table class="table-form">
			<colgroup>
				<col width="<?=Util::isMobile() ? '50px':'150px'?>" />
				<col />
			</colgroup>
			<tr>
				<th class="t">标签：</th>
				<td class="pl5 t"><input type="text" class="input_text" name="txtTitle" value="<?= $info ? $info->title : "" ?>"/> <span class="red">* 必须输入</span></td>
			</tr><tr>
				<th>排序：</th>
				<td class="pl5"><input type="text" class="input_text" name="txtOrder" value="<?= $info ? $info->orderby?:'' : "" ?>" /></td>
			</tr><tr>
				<td></td>
				<td class="pl5">
					<?php if(($id <1 && $user_right['add']) || $user_right['edit']):?>
					<input type="submit" class="input_button4" value=" 提交数据 " />　
					<?php endif;?>
					<input type="button" class="input_button2" value=" 返回列表 " onclick="history.go(-1);" />
				</td>
			</tr>
		</table>
		<?php if(($id <1 && $user_right['add']) || $user_right['edit']):?>
		</form>
		<?php endif;?>
	</div>
</div>

<script type="text/javascript">
happi.mood.admin.pageLoad();

$("input[type='submit']").click(function(){
	if ($("input[name='txtTitle']").val() == '') { $(document).alert({text:"请输入标签！", cancelClick:function(){ $("input[name='txtTitle']").focus(); }}); return false; };
	return true;
});
</script>