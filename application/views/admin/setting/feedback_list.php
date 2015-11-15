<div class="content-wrapper">
	<div class="content-operate">
		<span class="title"><img src="<?=RESOURCE?>images/ico/back.gif" class="ico" /> 用户反馈管理：</span>
	</div>
	<div class="content-list">
		<table class="gridview autowidth">
			<tr>
				<th class="center">编号</th>
				<th class="center">用户</th>
				<th class="center">反馈内容</th>
				<th class="center">时间</th>
			   	<th class="center e">管理</th>
			</tr>
			<?php if(count($list)==0):?>
			<tr><td colspan="5" class="center">暂无数据！</td></tr>
			<?php endif?>
			<?php foreach($list as $info):?>
			<tr>
				<td class="center"><?=$info->fid?></td>
				<td class="pl5"><?php if($info->uid) {?><a href="#" class="userControl" uid="<?=$info->uid?>"></a><?php } ?></td>
				<td class="pl5 tdnofix"><?=$info->feedback?></td>
				<td class="center"><?=date('Y-m-d H:i:s', $info->ltime)?></td>
			   	<td class="center">
					<?php if($user_right['delete']):?>
					<a href="<?=BASEURI?>admin/setting/feedback_delete?id=<?=$info->fid ?>?page=<?=$page?>" onclick="return confirm('删除不可恢复，您确实要删除吗？')"><img src="<?=RESOURCE?>images/ico/delete.gif" class="ico" /> 删除</a>
					<?php endif;?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
		<div class="pager t_r"></div>
	</div>
</div>
<script type="text/javascript">
happi.mood.admin.pageLoad(true);
$('.pager').pager({
	align: 'left',
	page: <?=$page?>,
	pageSize: 25,
	total: <?=$totals?>,
	showGo: true,
	url: "?page={0}"
}).width($(".gridview").width());
</script>
