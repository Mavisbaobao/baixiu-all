<?php

	require  __DIR__ .'/config.php';

	session_start();
	function checkLogin(){
		if(!isset($_SESSION['user_info'])){
			header('Location:/admin/login.php');
			exit();
		}
	}

	function connect(){
		$connection=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);
		if(!$connection){
			die('数据库连接失败');
		}
		mysqli_select_db($connection,DB_NAME);
		mysqli_set_charset($connection,DB_CHARSET);

		return $connection;
	}

	function fetch($result){
		$rows=array();
		while($row=mysqli_fetch_assoc($result)){
			$rows[]=$row;
		}
	
		return $rows;
	}

	function query($sql){
		$connection=connect();
		$result=mysqli_query($connection,$sql);
		$rows=fetch($result);
		return $rows;
	}

	function insert($table,$arr){

		$connection=connect();

		$keys=array_keys($arr);

		$values=array_values($arr);

	     $sql = "INSERT INTO " . $table . " (" . implode(", ", $keys) . ") VALUES('". implode("', '", $values) . "')";
	 //    print_r($sql);
		// exit();
		$result=mysqli_query($connection,$sql);
		// 返回插入的结果，当时true,false
		return $result;
	}

	function delete($sql){
		$connection=connect();

		$result=mysqli_query($connection,$sql);
		// print_r($result);
		// exit();
		return $result;
	}

	function update($table,$arr,$id){
		$connection=connect();
		$str='';
		foreach ($arr as $key => $val) {
			$str.=$key ."="."'".$val."',";
		}
		
		$str=substr($str, 0,-1);


		$sql="UPDATE ".$table ." SET " .$str." WHERE id=".$id;
		
		$result = mysqli_query($connection, $sql);
		
		return $result;
	}
 // 计算两个时间之间相差几个月

   function getMonthNum( $date1, $date2, $tags='-' ){
 $date1 = explode($tags,$date1);
 $date2 = explode($tags,$date2);
 return abs($date1[0] - $date2[0]) * 12 + abs($date1[1] - $date2[1]);
}
