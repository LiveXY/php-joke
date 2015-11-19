<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>猪猪笑话－搞笑段子、短信、无广告并且开源</title>
<meta name="description" content="猪猪笑话－搞笑段子、短信、无广告并且开源">
<meta name="keywords" content="笑话,搞笑,段子,短信">
</head>
<body>
	<h1>猪猪笑话－搞笑段子、短信、无广告并且开源</h1>
	<h2>最新笑话</h2>
	<ul>
	<?php foreach($jokes as $info):?>
		<li><a href="<?=BASEURI?>joke/<?=$info->jid?>"><h3><?=$info->title ? $info->title : mb_substr($info->joke, 0, 10,"utf-8").'...'?></h3></a><span><?=mb_substr($info->joke, 0, 30,"utf-8")?></span></li>
	<?php endforeach;?>
	</ul>
</body>
</html>