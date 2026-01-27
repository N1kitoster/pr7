<?php
if (isset($_SESSION["IdSession"])) {
    $IdSession = $_SESSION["IdSession"];

    $DateNow = date("Y-m-d H:i:s");
    $Sql = "UPDATE `session` set `DateNow` = '{$DateNow}' where `id` = {$IdSession}";
    $mysqli->query($Sql);
}
?>