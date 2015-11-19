<script type="text/javascript" src="<?=RESOURCE?>js/highcharts-3.0.4/js/highcharts.js"></script>
<script type="text/javascript" src="<?=RESOURCE?>js/my97datepicker/wdatepicker.js"></script>
<div class="content-wrapper">
	<div class="content-operate clearfix">
		<table class="table-search r" style="width:130px;">
			<tr>
				<td style="width: 120px"><input class="input_text120 Wdate Wdate120 WdateYM" type="text" id="month"  value="<?=date('Ym',$timestamp);?>" placeholder="请选择月份" /></td>
			</tr>
		</table>
		<span class="title"><img src="<?=RESOURCE?>images/ico/back.gif" class="ico" /> 统计：</span>
	</div>
	<div class="content-list">
		<div id="container"></div>
	</div>
</div>
<script type="text/javascript">
happi.pro.admin.pageLoad();

$("#month").unbind();

happi.pro.admin.initDate({
	onpicked:function(){
		location.href = "?month={0}".format($(this).val());
	}
});

$('#container').highcharts({
	credits: { text: '', url: '' },
	chart: { type: 'line' },
	title: { text: '<?=date('Y年m月份', $timestamp)?>统计' },
	subtitle: { text: '<a href="<?=BASEURI?>admin/report/reg?month=<?=date('Ym', strtotime('-1 month', $timestamp))?>">上个月</a> <a href="<?=BASEURI?>admin/report/reg?month=<?=date('Ym',$timestamp);?>">本月</a>  <a href="<?=BASEURI?>admin/report/reg?month=<?=date('Ym', strtotime('+1 month', $timestamp))?>">下个月</a>', useHTML: true },
	xAxis: {
		useHTML: true,
		categories:<?=isset($rows_month['registers']) ? json_encode(array_keys($rows_month['registers'])) :'[]'?>,
		labels: {
			rotation: -45,
			align: 'right',
			style: {
				fontSize: '13px',
				fontFamily: 'Verdana, sans-serif'
			}
		}
	},
	yAxis: {
		min: 0,
		allowDecimals: false,
		title: { text: '' }
	},
	tooltip: {
		shared: true,
	},
	plotOptions: {
		line: {
			dataLabels: {
				enabled: true
			},
			enableMouseTracking: false
		}
	},
	series: [{ name: '注册用户', data: <?=isset($rows_month['registers']) ? json_encode(array_values($rows_month['registers'])) : '[]'?>  },{ name: '活跃用户', data: <?=isset($rows_month['logins']) ? json_encode(array_values($rows_month['logins'])):'[]'?>}]
});

</script>
