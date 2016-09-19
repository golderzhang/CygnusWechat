<?php

	define("DATABASE_HOST", "localhost:3306"); // 数据库主机
// 	define("DATABASE_USERNAME", "zhangjinshi"); // 数据库用户名
// 	define("DATABASE_PASSWORD", "zhangjs");	// 数据库密码
	define("DATABASE_USERNAME", "golder"); // 数据库用户名
	define("DATABASE_PASSWORD", "golder");	// 数据库密码
	define("DATABASE_NAME", "cygnus_db"); // 数据库名
	
	
// 	$db = new DatabaseManager();
// 	$db->init();
	
	/*
	 * 数据库管理类
	 */
	class DatabaseManager {
		/*
		 * 初始化数据库
		 */
		public function init() {
			
			$connect = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD);
			if (mysqli_connect_errno($connect)) {
				echo "链接MySQL失败".mysqli_connect_error();
			}
				
			// 创建数据库
			$sql = "create database if not exists ".DATABASE_NAME.";";
			$result = mysqli_query($connect, $sql);
			
			// 选择数据库
			mysqli_select_db($connect, DATABASE_NAME);
				
			// 创建user表
			$sql = "create table if not exists user(subscribe varchar(2), openid varchar(100) primary key, nickname varchar(20), sex varchar(8), language varchar(20), city varchar(20), province varchar(20), country varchar(20), headimgurl varchar(255), subscribe_time varchar(20), unionid varchar(100), remark varchar(60), groupid varchar(100), delete varchar(10));";
			$result = mysqli_query($connect, $sql);
			
			// 创建goods表
			$sql = "create table if not exists goods(goodsid int primary key auto_increment, name varchar(20), remark varchar(80), category varchar(20), shipment varchar(100), costprice varchar(20), originprice varchar(20), discountprice varchar(20), addtime varchar(20), salestatus varchar(20), thumbimgurl varchar(100), detailurl1 varchar(100), detailurl2 varchar(100), detailurl3 varchar(100), delete varchar(10));";
            $result = mysqli_query($connect, $sql);
            
            // 创建orders表
            $sql = "create table if not exists orders(orderid int primary key auto_increment, goodsid int, groupid varchar(30), customerphone varchar(20), customername varchar(20), unitprice varchar(20), count varchar(10), dealtime varchar(20), delete varchar(10));";
			$result = mysqli_query($connect, $sql);
            
			mysqli_close($connect);
		}
	}
?>