<?php
	session_start();
	include("../settings/connect_datebase.php");
	
	$login = $_POST['login'];
	
	// ищем пользователя
	$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."';");
	
	$id = -1;
	if($user_read = $query_user->fetch_row()) {
		// создаём новый пароль
		$id = $user_read[0];
	}
	
	function PasswordGeneration() {
		// создаём пароль
		$chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP"; // матрица
		$max=10; // количество
		$size=StrLen($chars)-1; // Определяем количество символов в $chars
		$password="";
		
		while($max--) {
			$password.=$chars[rand(0,$size)];
		}
		
		return $password;
	}
	
	if($id != 0) {
		//обновляем пароль
		$password = PasswordGeneration();;
		// проверяем не используется ли пароль 
		$query_password = $mysqli->query("SELECT * FROM `users` WHERE `password`= '".md5($password)."';");
		while($password_read = $query_password->fetch_row()) {
			// создаём новый пароль
			$password = PasswordGeneration();
		}
		// обновляем пароль
		$mysqli->query("UPDATE `users` SET `password`='".md5($password)."' WHERE `login` = '".$login."'");
		// отсылаем на почту
		//mail($login, 'Безопасность web-приложений КГАПОУ "Авиатехникум"', "Ваш пароль был только что изменён. Новый пароль: ".$password);
	
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
		$Sql ="INSERT INTO `logs`( `ip`, `IdUser`, `Date`, `TimeOnline`, `Event`) VALUES ('{$ip}','{$id}','{$DateStart}','00:00:00','Челик под ником {$login} успешно поменял пароль')";
		$mysqli->query($Sql);
	
		}
	
	echo $id;
?>