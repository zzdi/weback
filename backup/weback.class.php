<?php
/*
备份还原数据库和网站操作
数据库连接方式支持mysql和mysqli
*/

class weback
{
	public function __construct(){
		$this->connect();
	}
	function connect(){
		$this->mysql = DB_LINK=='mysql'?mysql_connect(DB_HOST,DB_USER,DB_PASSWORD):new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
		if(!$this->mysql){
			exit("数据库连接错误");
		}
		if(DB_LINK=='mysql'){
			if(!@mysql_select_db(DB_NAME,$this->mysql)){
				exit("数据库".DB_NAME."不存在");
			}
		}
		$this->query("set names ".DB_CHARSET);
	}
	function disconnect(){
		return DB_LINK=='mysql' ? mysql_close($this->mysql):$this->mysql->close();
	}
	function query($sql){
		return DB_LINK=='mysql' ? mysql_query($sql,$this->mysql) : $this->mysql->query($sql) ;
	}

	function fetch_asc($sql){
		$result = $this->query($sql);
		$arr = array();
		if(DB_LINK=='mysql'){
			while($rows = mysql_fetch_assoc($result)){
				$arr[]=$rows;
			}
			mysql_free_result($result);
		}else{
			while($rows = $result->fetch_array(MYSQLI_ASSOC)){
				$arr[]=$rows;
			}
			$result->close();
		}
		return $arr;
	}


	function db_back(){
		$rel2 = $this->fetch_asc('SHOW TABLE STATUS FROM '.DB_NAME);
		$db = array();
		foreach($rel2 as $key=>$value){
			if(substr($value['Name'],0,strlen(DB_PRE))==DB_PRE){
				$db[] = $value['Name'];
			}
		}
		$sql = "";
		foreach($db as $k=>$v){
			$rel = $this->fetch_asc('SHOW CREATE TABLE '.$v);
			$sql .= "DROP TABLE IF EXISTS `".$v."`;\n";
			$sql .= $rel[0]['Create Table'].";\n";
			$record = $this->fetch_asc("select * from ".$v);
			if(!empty($record)){
				$insert=array();
				foreach($record as $key=>$value){
					foreach($value as $r_k=>$r_v){
						$insert[$r_k] = DB_LINK=='mysql' ? "'".mysql_real_escape_string($r_v)."'" : "'".mysqli_real_escape_string($this->mysql,$r_v)."'" ;
					}
					$sql.="INSERT INTO `".$v."` VALUES(".implode(',',$insert).");\n";
				}
			}
		}
		if(!@file_put_contents(DB_BACK,$sql)){
			exit('备份失败,请检查文件夹是否有足够的权限');
		}
	}
	//还原数据库
	function db_import(){
		if(IMPORT_TYPE==1){
			$this->query("drop database ".DB_NAME);
			$this->query("create database ".DB_NAME);
			$this->connect();
		}
		$data = @file_get_contents(DB_BACK);
		$data = explode(";\n",trim($data));
		if(!empty($data)){
			foreach($data as $k=>$v){
				$this->query($v);
			}
		}
		//echo "数据还原成功";
	}

	//zip打包压缩网站
	function web_back(){
		$dir="../";
		$zip = new ZipArchive();
		$filename = date('Ymdhis').'.zip';
		if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
			exit("无法创建 <$filename>n");
		}
		$files = $this->list_dir($dir);
		foreach($files as $path){
			$zip->addFile($path,str_replace("./","",str_replace("\\","/",$path)));
		}
		$zip->addFile($dir.WEB_BACK.'/'.DB_BACK);
		$zip->close();
		echo "网站已备份为：$filename <br/>";
		unlink(DB_BACK);
	}

	function list_dir($dir='.') {
		$files = array();
		if (is_dir($dir)) {
			$fh = opendir($dir);
			while (($file = readdir($fh)) !== false) {
				if (strcmp($file, '.')==0 || strcmp($file, '..')==0 || strcmp($file, WEB_BACK)==0){ continue; }
				$filepath = $dir . '/' . $file;
				if ( is_dir($filepath) ){
					$files = array_merge($files, $this->list_dir($filepath));
				}else{
					array_push($files, $filepath);
				}
			}
			closedir($fh);
		} else {
			$files = false;
		}
		return $files;
	}
	//解压还原网站
	function web_import($file){
		$zip = new ZipArchive() ;
		if ($zip->open($file) !== TRUE) {
			exit("不存在备份文件:$file");
		}
		$zip->extractTo('..');
		$zip->close();
	}

	//删除网站
	function del_dir($dir) {
		$dh = opendir($dir);
		while ($file=readdir($dh)) {
			if (strcmp($file, '.')==0 || strcmp($file, '..')==0 || strcmp($file, WEB_BACK)==0){ continue; }
			$fullpath=$dir."/".$file;
			if(!is_dir($fullpath)) {
				unlink($fullpath);
			} else {
				$this->del_dir($fullpath);
			}
		}
		closedir($dh);
	}
}