<div class="content-wrapper">
	<div class="content-operate">
		<span class="title"><img src="<?=RESOURCE?>images/ico/back.gif" class="ico" /> 版本管理：</span>
	</div>
	<div class="content-list">
		<table class="gridview autowidth">
			<tr>
				<th class="center">版本号</th>
				<th class="center">版本名称</th>
				<th class="center">内容</th>
				<th class="center">状态</th>
				<th class="center">时间</th>
			   	<th class="center e">管理 <?php if ($user_right['add']):?><a class="tab_open" href="<?=BASEURI?>admin/sys/version_op" id="addVersion"><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加版本</a><?php endif?></th>
			</tr>
			<?php if(count($list)==0):?>
			<tr><td colspan="12" class="center">暂无数据！</td></tr>
			<?php endif?>
			<?php foreach($list as $info):?>
			<tr>
				<td class="pl5"><?=$info->vid?></td>
				<td class="pl5"><?=$info->vname?></td>
				<td class="pl5"><?=$info->vtext?></td>
				<td class="pl5"><?=Util::getStatus($info->status)?></td>
				<td class="center"><?=date('Y-m-d H:i:s', $info->ltime)?></td>
			   	<td class="center">
					<?php if($user_right['edit']):?>
					<a class="tab_open" id="userEdit<?=$info->vid ?>" href="<?=BASEURI?>admin/sys/version_op?id=<?=$info->vid ?>"><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" /> 修改版本</a>
					<?php else:?>
					<a href="<?=BASEURI?>admin/sys/version_op?id=<?=$info->vid ?>"><img src="<?=RESOURCE?>images/ico/view.gif" class="ico" /> 查看版本</a>
					<?php endif;?>
					<?php if($user_right['delete']):?>
					<a href="<?=BASEURI?>admin/sys/version_delete?id=<?=$info->vid ?>" onclick="return confirm('删除不可恢复，您确实要删除版本吗？')"><img src="<?=RESOURCE?>images/ico/delete.gif" class="ico" /> 删除版本</a>
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
</script>