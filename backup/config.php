<?php
/*
设置数据库连接配置
设置连接类型mysql或mysqli
设置存放备份后的压缩包
设置还原时是否先进行备份
设置还原方式，清空再还原或覆盖还原
*/
//数据库地址
define('DB_HOST', '');
//数据库用户名
define('DB_USER', '');
//数据库密码
define('DB_PASSWORD','');
//数据库名
define('DB_NAME', '');
//数据库表前缀
define('DB_PRE', '');
//数据库编码
define('DB_CHARSET', 'utf8');

//数据库连接类型,mysql 或 mysqli
define('DB_LINK', 'mysql');

//当前文件所在目录，请正确填写
define('WEB_BACK', 'backup');

//备份或导入的sql文件，会储存压缩包的当前目录下
define('DB_BACK', 'back.sql');

//还原前是否先备份一次，1为先备份再还原，0为不备份直接还原。
define('IMPORT_BACK', 1);

//还原方式，1为完全一致还原（删除现有数据库和网站所有内容后再还原），0覆盖还原（备份后新增加的数据库表，上传的网站文件等不会被删除）。两种还原方式新增加的字段和行记录都会还原到备份时状态
define('IMPORT_TYPE', 1);

//设置超时，网站较大时可以设置大一点
set_time_limit(9999);