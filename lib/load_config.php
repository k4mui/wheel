<?php
/*
* loads server config file /config.json
*/
$config = json_decode(file_get_contents(__DIR__."/../config.json"), true);
?>