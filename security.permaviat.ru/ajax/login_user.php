<?php
	session_start();
	include("../settings/connect_datebase.php");
	
	$login = $_POST['login'];
	$password = $_POST['password'];
	
	// ищем пользователя
	$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."' AND `password`= '".$password."';");
	
	$id = -1;
	while($user_read = $query_user->fetch_row()) {
		$id = $user_read[0];
	}
	
	if($id != -1) {
		$_SESSION['user'] = $id;
		$ip = $_SERVER["REMOTE_ADDR"];
		$DateStart = date("Y-m-d H:i:s");
		$Sql = "INSERT INTO `session`(`IdUser`, `Ip`, `DateStart`, `DateNow`) VALUES ( {$id} , '{$ip}' , '{$DateStart}' , '{$DateStart}' )";
		echo $Sql;
		$mysqli->query($Sql);
		$Sql = "SELECT `id` FROM `session` WHERE `DateStart` = '{$DateStart}'";
		$query = $mysqli->query($Sql);
		$read = $query->fetch_assoc();
		$_SESSION["IdSession"] = $read["id"];

		$Sql ="INSERT INTO `logs`( `ip`, `IdUser`, `Date`, `TimeOnline`, `Event`) VALUES ('{$ip}','{$id}','{$DateStart}','00:00:00','Челик под ником {$login} успешно авторизовался')";
		$mysqli->query($Sql);
	}

	
	echo md5(md5($id));
?>