<!DOCTYPE html>
<html>
<head>
<meta name="baidu-site-verification" content="IkYLMmQfq8" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>猪猪笑话－笑话,段子,短信,搞笑,爆笑,励志,幽默,冷笑话,开心,娱乐,成人笑话,好心情</title>
<meta name="description" content="猪猪笑话精选大量最新笑话，汇集互联网上各类新鲜流行的笑话段子，提供大量经典笑话、幽默笑话、成人笑话、爆笑笑话、冷笑话等，让你每天笑一笑，学习工作没烦恼，能给您带来轻松快乐的心情。">
<meta name="keywords" content="笑话,段子,短信,搞笑,爆笑,励志,幽默,冷笑话,开心,娱乐,成人笑话,好心情">
</head>
<body>
	<h1>猪猪笑话精选大量最新笑话，汇集互联网上各类新鲜流行的笑话段子，提供大量经典笑话、幽默笑话、成人笑话、爆笑笑话、冷笑话等，让你每天笑一笑，学习工作没烦恼，能给您带来轻松快乐的心情。</h1>
	<ul>
		<li><h2>开源：<a href="https://github.com/livexy" target="_blank">https://github.com/livexy</a></h2></li>
		<li><h2>IOS下载地址：<a href="https://itunes.apple.com/cn/app/dared/id1059711002" target="_blank">https://itunes.apple.com/cn/app/dared/id1059711002</a></h2></li>
		<li><h2>ANDROID下载地址：<a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.livexy.joke" target="_blank">http://a.app.qq.com/o/simple.jsp?pkgname=com.livexy.joke</a></h2></li>
	</ul>
	<h2>最新笑话</h2>
	<ul>
	<?php foreach($jokes as $info):?>
		<li><a href="<?=BASEURI?>joke/<?=$info->jid?>" title="<?=$info->title ? $info->title : mb_substr($info->joke, 0, 10,"utf-8").'...'?>"><h3><?=$info->title ? $info->title : mb_substr($info->joke, 0, 10,"utf-8").'...'?></h3></a><p><?=mb_substr($info->joke, 0, 50, "utf-8")?>...</p></li>
	<?php endforeach;?>
	</ul>
</body>
</html>