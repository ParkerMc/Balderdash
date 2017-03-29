<?php
function randomString($len){ // Generate a random string
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < $len; $i++){ // Loop for every letter/number
        $randstring .= $characters[rand(0, strlen($characters)-1)]; // Randomly chooseing letter/number
    }
    return $randstring;
}
function getRealIpAddr(){// Get the user's ip
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){ // Check ip from share internet
      	$ip=$_SERVER['HTTP_CLIENT_IP'];
    }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ // To check ip is pass from proxy
      	$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{ // Else get ip from remote_addr varable
      	$ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
?>