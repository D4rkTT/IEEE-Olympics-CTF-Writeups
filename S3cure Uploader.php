<?php
$date = "2020-09-27 17:00:16";
for ($i=1; $i <= 1000; $i++){
    echo md5($i . $date . "test0x4148fo") . ".php <br>";
}