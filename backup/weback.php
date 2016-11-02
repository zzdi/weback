<?php
/*
备份：直接访问当前文件
还原：weback.php?file=20161101182122.zip  参数为备份文件夹下的压缩包名，还原时请确认压缩包中sql数据库路径和config.php中的配置一致。
*/
include('./config.php');
include('./weback.class.php');

$weback = new weback();

$file = @$_GET['file'];
if(empty($file)||IMPORT_BACK==1){
	//备份数据库
	$weback->db_back();
	//备份网站
	$weback->web_back();
}

if(!empty($file)){
	//删除网站内容
	if(IMPORT_TYPE==1){
		$weback->del_dir('..');
	}
	//还原网站
	$weback->web_import($file);
	//还原数据库
	$weback->db_import();
	echo '还原网站成功！';
}

$weback->disconnect();