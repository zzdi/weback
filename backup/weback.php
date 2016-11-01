<?php
/*
备份：直接访问当前文件
还原：weback.php?file=20161101182122.zip  参数为备份文件夹下的压缩包名，还原时请确认压缩包中sql数据库路径和config.php中的配置一致。
*/
include('./config.php');
include('./weback.class.php');

$weback = new weback();

//还原前也会先备份一次，如果还原的时候不需要可以注释掉再操作还原
//备份数据库
$weback->db_back();
//备份网站
$weback->web_back();

$file = @$_GET['file'];
if(!empty($file)){
	//删除网站内容
	$weback->del_dir('..');
	//还原网站
	$weback->web_import($file);
	//还原数据库
	$weback->db_import();
	echo '还原网站成功！';
}