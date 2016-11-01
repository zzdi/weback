<?php
/*
设置备份的数据库
设置存放备份后的压缩包
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
define('DB_PCONNECT', 0);
//数据库编码
define('DB_CHARSET', 'utf8');

//当前文件所在目录，请正确填写
define('WEB_BACK', 'backup');

//备份或导入的sql文件，会储存压缩包的当前目录下
define('DB_BACK', 'back.sql');

set_time_limit(9999);