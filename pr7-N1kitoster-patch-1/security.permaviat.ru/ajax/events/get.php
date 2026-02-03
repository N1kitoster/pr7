<?
    require_once("../../settings/connect_datebase.php");

    $sql = "SELECT * FROM `logs` ORDER BY `Date`";
    $query = $mysqli->query($sql);
    $events = array();
    while ($read = $query->fetch_assoc()) {
        $status = "";

        
		$SqlSession = "SELECT * FROM `session` WHERE `IdUser` = {$read["IdUser"]} order by 'DateStart' DESC";
		$querySess = $mysqli->query($SqlSession);
			if ($querySess->num_rows>1) {
			$readSess = $querySess->fetch_assoc();
			//$read = $querySess->fetch_assoc();
			$TimeEnd = strtotime($readSess["DateNow"]) +5*60;
			$TimeNow=time();

                if ($TimeEnd > $TimeNow) {
                    $status = "online";
                }
                else {
                    $TimeEnd = strtotime($readSess["DateNow"]);
                    $TimeDelta = round(($TimeNow - $TimeEnd)/60);
                    $status =  "Был в сети - ".$TimeDelta." минут назад";
                }

			
			
		} 
        


        $event = array(
            "id" =>$read["id"],
            "ip" =>$read["ip"],
            "Date" =>$read["Date"],
            "TimeOnline" =>$read["TimeOnline"],
            "status" =>$status,
            "Event" =>$read["Event"]
        );
        array_push($events, $event);
    }
    echo json_encode($events, JSON_UNESCAPED_UNICODE);
?>