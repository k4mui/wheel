<?php

//$root = dirname(__FILE__);
//require "$root/includes/init.php";
//require "$root/includes/ip_info.php";


//$api_token = '45769edaba6feed4489cc3bf5340983817d2855c64eea6b7';
//$ip = '192.140.254.243';//getRealIpAddr();
//$ip = '35.185.119.81';
//$js = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=$ip"), true);
//$js = json_decode(file_get_contents("https://usercountry.com/v1.0/json/$ip?token=$api_token"), true);
//echo "City: {$js['region']['city']}<br>";
//print_r($js);
/*echo $xml->geoplugin_countryCode."<br>";
echo $xml->geoplugin_countryName."<br>";

echo $xml->geoplugin_regionCode."<br>";
echo $xml->geoplugin_regionName."<br>";

echo $xml->geoplugin_city."<br>";
*/
/*
echo "<pre>";
foreach ($xml as $key => $value)
{
    echo $key , "= " , $value ,  " \n" ;
}
echo "</pre>";
*/

//$token = bin2hex(random_bytes(16));
//$v = null;
//$token = md5(openssl_random_pseudo_bytes(64));
//echo $token;
$tz = DateTimeZone::listIdentifiers();
//echo "\"timezone_id\",\"timezone_name\"<br>";
foreach($tz as $v) {
    echo "('$v'), <br>";
    //echo "NULL,\"$v\"<br>";
}
?>