<div class="content-wrapper">
	<div class="content-operate">
		<table class="table-search autowidth">
			<tr>
				<td><img src="<?=RESOURCE?>images/ico/search.gif" class="ico" /> 笑话管理：</td>
				<td><input class="input_text120 text_gray" type="text" id="key" value="<?=$key?>" placeholder="请输入关键字" /></td>
				<td><button type="submit" id="btnSearch" class="input_button4"><img src="<?=RESOURCE?>images/ico/search.gif" class="ico" /> 搜索</button></td>
			</tr>
		</table>
	</div>
	<div class="content-list">
		<table class="gridview autowidth">
			<tr>
				<th class="center">编号</th>
				<th class="center">标题</th>
				<th class="center">内容</th>
			   	<th class="center e">管理</th>
			</tr>
			<?php if(count($list)==0):?>
			<tr><td colspan="12" class="center">暂无数据！</td></tr>
			<?php endif?>
			<?php foreach($list as $info):?>
			<tr>
				<td class="pl5"><?=$info->jid?></td>
				<td class="pl5"><?=$info->title?></td>
				<td class="pl5 tdnofix"><?=$info->joke?></td>
			   	<td class="center">
					<?php if($user_right['edit']):?>
					<a class="tab_open" id="userAudit<?=$info->jid ?>" href="<?=BASEURI?>admin/setting/audit_op?id=<?=$info->jid ?>"><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" /> 审核笑话</a>
					<?php else:?>
					<a href="<?=BASEURI?>admin/setting/audit_op?id=<?=$info->jid ?>"><img src="<?=RESOURCE?>images/ico/view.gif" class="ico" /> 查看笑话</a>
					<?php endif;?>
					<?php if($user_right['delete']):?>
					<a href="<?=BASEURI?>admin/setting/audit_delete?id=<?=$info->jid ?>&key=<?=$key?>&page=<?=$page?>" onclick="return confirm('删除不可恢复，您确实要删除笑话吗？')"><img src="<?=RESOURCE?>images/ico/delete.gif" class="ico" /> 删除笑话</a>
					<?php endif;?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
		<div class="pager t_r"></div>
	</div>
</div>
<script type="text/javascript">
happi.pro.admin.pageLoad(false);
$("#btnSearch").click(function () {
	top.tab.refresh();
	location.href = "?key={0}&page=1".format($("#key").val());
}).btnSubmit($(".table-search"));
$('.pager').pager({
	align: 'left',
	page: <?=$page?>,
	pageSize: 15,
	total: <?=$totals?>,
	showGo: true,
	url: "?page={0}&key=" + $("#key").val()
}).width($(".gridview").width());
</script>