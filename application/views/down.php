<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="viewport" content="height=device-height, width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
<title>正在跳转 - 猪猪笑话</title>
<style type="text/css">
html,body {padding:0;margin:0;background:#24604C;}
.tip { position:absolute; top:8px; left:5px; right:5px; height: 80px; text-align:left; padding:10px; background-color:#FEFDEF; line-height:25px; border-radius:8px; font-size:18px; }
.tip:after {content: '';position: absolute; width: 0; height: 0; border-style: solid; border-width: 0 8px 8px 8px; border-color: transparent transparent #FEFDEF transparent; top:-7px; right:10px; }
.tip img { float:left; margin-left:0px; padding-right:10px; }
.tip span, .tip b { color:#E95352; }
</style>
</head>
<body>
<?php if($t==1) {?>
	<div class="tip">
		<img src="<?=RESOURCE?>images/80.png" />
		下载 <b>猪猪笑话</b> 请点击右上角按钮选择“<span>在Safari中打开</span>”
	</div>
	<script type="text/javascript"> location.href = 'https://itunes.apple.com/cn/app/dared/id'; </script>
<?php } else {?>
	<div class="tip">
		<img src="<?=RESOURCE?>images/80.png" />
		下载 <b>猪猪笑话</b> 请点击右上角按钮选择“<span>在浏览器中打开</span>”
	</div>
	<script type="text/javascript"> location.href = '<?=ROOTURL?>/joke.apk'; </script>
<?php } ?>
</body>
</html>