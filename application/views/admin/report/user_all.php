<script type="text/javascript" src="<?=RESOURCE?>js/my97datepicker/wdatepicker.js"></script>
<?php $registers=0; ?>
<div class="content-wrapper">
	<table class="table-search">
		<tr>
			<td><img src="<?=RESOURCE?>images/ico/search.gif" class="ico" /> 每日总表：</td>
			<td>
				<input class="input_text120 Wdate Wdate120 WdateYM" type="text" id="month"  value="<?=$month;?>" placeholder="请选择月份" />
			</td>
			<td style="width: 140px"><button type="submit" id="btnSearch" class="input_button4"><img src="<?=RESOURCE?>images/ico/search.gif" class="ico" /> 搜索</button></td>
			<td class="red">每小时第10分钟统计数据</td>
		</tr>
	</table>
	<div class="content-list">
		<table class="gridview autowidth ">
			<tr>
				<th class="center th2">日期</th>
				<th class="center th2">总用户数</th>
				<th class="center th2">注册用户</th>
				<th class="center th2">活跃用户</th>
			</tr>
			<?php foreach($list as $info):?>
			<?php $registers+=$info->registers; ?>
			<tr>
				<td class="pl5"><?=date('Y-m-d',strtotime($info->day))?></td>
				<td class="right pr5"><?=number_format($info->totals)?></td>
				<td class="right pr5"><?php if ($info->registers>0) {?><a title="點擊查看詳細" id="RegUsers<?=$info->day?>" href="<?=BASEURI?>admin/sys/user_list?begin=<?=substr($info->day, 0, 4).'-'.substr($info->day, 4, 2).'-'.substr($info->day, 6, 2)?>&end=<?=substr($info->day, 0, 4).'-'.substr($info->day, 4, 2).'-'.substr($info->day, 6, 2)?>" class="tab_open"><img src="<?=RESOURCE?>images/ico/view2.gif" class="ico"> <?=number_format($info->registers)?></a><?php } else {?>0<?php } ?></td>
				<td class="right pr5"><?=number_format($info->logins)?></td>
			</tr>
			<?php endforeach;?>
			<tr class="footer">
				<td class="right pr5 "></td>
				<td class="right pr5"></td>
				<td class="right pr5"><?=number_format($registers);?></td>
				<td class="right pr5"></td>
			</tr>
		</table>
		<div class="pager t_l"></div>
	</div>
</div>
<script type="text/javascript">
happi.pro.admin.pageLoad(false);
$("#btnSearch").click(function () {
	top.tab.refresh();
	var month = $("#month").val();
	location.href = "?month={0}&page=1".format(month);
}).btnSubmit($(".table-search"));
</script>