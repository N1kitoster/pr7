<?
    session_start();
	include("../settings/connect_datebase.php");

    $IdUser = $_SESSION['user'];
    $Message = $_POST["Message"];
    $IdPost = $_POST["IdPost"];
    $IdSession = $_SESSION["IdSession"];

    $sql =  "SELECT `session`.*, `users`.`login`
			FROM `session` `session`
			JOIN `users` `users` ON `users`.`id` = `session`.`IdUser` 
			WHERE `session`.`id` = {$IdSession};";
	$query = $mysqli->query($sql);
	$read= $query->fetch_array();

	$TimeStart = strtotime($read["DateStart"]);
	$TimeNow= time();
	$Ip = $read["Ip"];
	$TimeDelta = gmdate("H:i:s", $TimeNow - $TimeStart);
	$Date = date("Y-m-d H:i:s");
	$login = $read["login"];
	$sql = "INSERT INTO `logs`(`ip`, `IdUser`, `Date`, `TimeOnline`, `Event`) VALUES ('{$Ip}','{$IdUser}','{$Date}','{$TimeDelta}','Челик с ником ".$login." оставил коменнт под постом с номером ".$IdPost."')";
	$mysqli->query($sql); 


    $mysqli->query("INSERT INTO `comments`(`IdUser`, `IdPost`, `Messages`) VALUES ({$IdUser}, {$IdPost}, '{$Message}');");
?>