<?php
	session_start();
	include("../settings/connect_datebase.php");
	
	$login = $_POST['login'];
	$password = $_POST['password'];
	
	// ищем пользователя
	$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."'");
	$id = -1;
	
	if($user_read = $query_user->fetch_row()) {
		echo $id;
	} else {
		$mysqli->query("INSERT INTO `users`(`login`, `password`, `roll`) VALUES ('".$login."', '".$password."', 0)");
		
		$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."' AND `password`= '".$password."';");
		$user_new = $query_user->fetch_row();
		$id = $user_new[0];
			
		if($id != -1) $_SESSION['user'] = $id; // запоминаем пользователя
		echo $id;


		$ip = $_SERVER["REMOTE_ADDR"];
		$DateStart = date("Y-m-d H:i:s");
		$Sql = "INSERT INTO `session`(`IdUser`, `Ip`, `DateStart`, `DateNow`) VALUES ( {$id} , '{$ip}' , '{$DateStart}' , '{$DateStart}' )";
		$mysqli->query($Sql);
		
		$Sql = "SELECT `id` FROM `session` WHERE `DateStart` = '{$DateStart}'";
	 	$query = $mysqli->query($Sql);
		$read = $query->fetch_assoc();
		$_SESSION["IdSession"] = $read["id"];
		$Sql ="INSERT INTO `logs`( `ip`, `IdUser`, `Date`, `TimeOnline`, `Event`) VALUES ('{$ip}','{$id}','{$DateStart}','00:00:00','Челик под ником {$login} успешно зарегался')";
		$mysqli->query($Sql);
		}
?>

