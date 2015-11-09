<div class="content-wrapper">
	<div class="content-operate">
		<span class="title"><img src="<?=RESOURCE?>images/ico/back.gif" class="ico" /> 标签管理：</span>
		<span class="red">注：排序规则数字越大排前面，编号小于30是笑话大于30是美图</span>
	</div>
	<div class="content-list">
		<table class="gridview autowidth">
			<tr>
				<th class="center">编号</th>
				<th class="center">标签</th>
				<th class="center">排序</th>
				<th class="center">数量</th>
			   	<th class="center e">管理 <?php if ($user_right['add']):?><a href="<?=BASEURI?>admin/setting/tag_op?id=-1"><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加标签</a><?php endif?></th>
			</tr>
			<?php if(count($list)==0):?>
			<tr><td colspan="5" class="center">暂无数据！ <?php if ($user_right['add']):?><a href="<?=BASEURI?>admin/setting/tag_op?id=-1"><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加标签</a><?php endif?></td></tr>
			<?php endif?>
			<?php foreach($list as $info):?>
			<tr>
				<td class="pl5"><?=$info->tid?></td>
				<td class="pl5"><?=$info->title?></td>
				<td class="pl5"><?=$info->orderby ?: '' ?></td>
				<td class="pl5"><?=$info->totals?></td>
			   	<td class="center">
					<?php if($user_right['edit']):?>
					<a href="<?=BASEURI?>admin/setting/tag_op?id=<?=$info->tid ?>"><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" /> 修改标签</a>
					<?php else:?>
					<a href="<?=BASEURI?>admin/setting/tag_op?id=<?=$info->tid ?>"><img src="<?=RESOURCE?>images/ico/view.gif" class="ico" /> 查看标签</a>
					<?php endif;?>
					<?php if($user_right['delete']):?>
					<a href="<?=BASEURI?>admin/setting/tag_delete?id=<?=$info->tid ?>" onclick="return confirm('删除不可恢复，您确实要删除标签吗？')"><img src="<?=RESOURCE?>images/ico/delete.gif" class="ico" /> 删除标签</a>
					<?php endif;?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
		<div class="pager t_r"></div>
	</div>
</div>
<script type="text/javascript">
happi.mood.admin.pageLoad(false);
</script>