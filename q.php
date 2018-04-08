<?php
require_once(__DIR__.'/includes/classes/database.php');
$da = data_access::get_instance();
$files = scandir('/home/obi/Downloads/sp');
foreach($files as $file) {
    if (substr($file, 0, 4) === 'outg') {
        $da->_load($file);
    }
}
unset($da);
?>