<div class="content-wrapper">
	<div class="content-operate">
		<table class="table-search autowidth">
			<tr>
				<td><img src="<?=RESOURCE?>images/ico/search.gif" class="ico" /> 笑话管理：</td>
				<td style="width:120px"><div class="input_cbo input_cbo120"><label>全部笑话</label>
					<select id="tid" name="tid">
						<option value="0">全部笑话</option>
						<?php foreach($tags as $t):?>
							<?php if ($t->tid>=30 || empty($t->title)) continue;?>
						<option value="<?= $t->tid ?>" <?= ($tid && $t->tid==$tid) ? "selected=selected" : "" ?>><?= $t->title ?></option>
						<?php endforeach;?>
					</select>
				</div></td>
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
				<th class="center">标签</th>
				<th class="center">喜欢</th>
				<th class="center">时间</th>
			   	<th class="center e">管理 <?php if ($user_right['add']):?><a class="tab_open" href="<?=BASEURI?>admin/setting/joke_op" id="addjoke"><img src="<?=RESOURCE?>images/ico/add.gif" class="ico" /> 添加笑话</a><?php endif?></th>
			</tr>
			<?php if(count($list)==0):?>
			<tr><td colspan="12" class="center">暂无数据！</td></tr>
			<?php endif?>
			<?php foreach($list as $info):?>
			<tr>
				<td class="pl5"><?=$info->jid?></td>
				<td class="pl5"><?=$info->title?></td>
				<td class="pl5 tdnofix"><?=str_replace("\n", '<br/>', $info->joke)?></td>
				<td class="pl5"><?php $vs = explode(';', $info->tags); foreach($vs as $t) {?><?= isset($tags[$t]) ? $tags[$t]->title : '' ?>;<?php }?></td>
				<td class="pl5"><?=$info->likes?></td>
				<td class="center"><?=date('Y-m-d H:i:s', $info->ltime)?></td>
			   	<td class="center">
					<?php if($user_right['edit']):?>
					<a class="tab_open" id="userEdit<?=$info->jid ?>" href="<?=BASEURI?>admin/setting/joke_op?id=<?=$info->jid ?>"><img src="<?=RESOURCE?>images/ico/edit.gif" class="ico" /> 修改笑话</a>
					<?php else:?>
					<a href="<?=BASEURI?>admin/setting/joke_op?id=<?=$info->jid ?>"><img src="<?=RESOURCE?>images/ico/view.gif" class="ico" /> 查看笑话</a>
					<?php endif;?>
					<?php if($user_right['delete']):?>
					<a href="<?=BASEURI?>admin/setting/joke_delete?id=<?=$info->jid ?>&tid=<?=$tid?>&key=<?=$key?>&page=<?=$page?>" onclick="return confirm('删除不可恢复，您确实要删除笑话吗？')"><img src="<?=RESOURCE?>images/ico/delete.gif" class="ico" /> 删除笑话</a>
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
	location.href = "?key={0}&tid={1}&page=1".format($("#key").val(),$("#tid").val());
}).btnSubmit($(".table-search"));
$('.pager').pager({
	align: 'left',
	page: <?=$page?>,
	pageSize: 25,
	total: <?=$totals?>,
	showGo: true,
	url: "?page={0}&tid=" + $("#tid").val() + "&key=" + $("#key").val()
}).width($(".gridview").width());
</script>