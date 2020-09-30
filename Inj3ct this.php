<?php
$handle = curl_init();
 
$url = "http://207.154.255.223/inj3ct_easy/user_info.php?uid=1";
$proxy = 'http://127.0.0.1:8080';
curl_setopt($handle, CURLOPT_URL, $url);

curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
#curl_setopt($handle, CURLOPT_PROXY, $proxy);
curl_setopt($handle, CURLOPT_COOKIE, 'PHPSESSID=ufcrbmg1ckodev6n7gktomdnvv');
curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:80.0) Gecko/20100101 Firefox/80.0");
$output = curl_exec($handle);

curl_close($handle);
$output = str_replace("<script>document.cookie=\"token=","",$output);
$output = str_replace("\"</script><meta http-equiv=\"refresh\" content=\"time; URL=user_info.php?uid=1\"/>","",$output);

setcookie("token", $output);