<?php

set_time_limit(0);
$channel = 1;
$url = "http://beam3.sotal-iptv.com/output/first/playlist.m3u8";
$phpExePaht = "C:/xampp/php/php.exe";
$dateFormat = "YmdHis";
date_default_timezone_set("Europe/Moscow");

$now = date($dateFormat);
$dateTimeNow = new DateTime($now);

$srcDom = new DOMDocument;
// i need to allow the file to be chagned automatically change the file automatically
$srcDom->load(__DIR__ . '/../web/xmls/2013-05-27_xtv.xml');
$xPath = new DOMXPath($srcDom);
$xmlChannel = $xPath->query('/tv/programme[@channel="' . $channel . '" and substring(@start,1,14)>' . $now . ']');

$count = 0;
$totalstart = microtime(true);

foreach ($xmlChannel as $program) {

    $start = new DateTime($program->getAttribute("start"));
    $stop = new DateTime($program->getAttribute("stop"));
    print_r($dateTimeNow);
    echo "<br>";
    print_r($start);
    echo "<br>";
    print_r($stop);
    echo "<br>";
    $length = $start->diff($stop);
    print_r($length);
    echo "<br>";
    $now = date($dateFormat);
    $dateTimeNow = new DateTime($now);
    $timeToStart = $dateTimeNow->diff($start);
    print_r($timeToStart);
    echo "<br>";


    $lengthInS = ($length->h * 3600) + ($length->i * 60) + $length->s + 140;

    $videoName = $start->format($dateFormat) . ".mkv";
    
    $wait=($timeToStart->h * 3600) + ($timeToStart->i * 60) + $timeToStart->s - 70;
    if ($wait<0) {
        $wait=0;
    }
    //sleep($wait);
    //start recording procces

    $cmd = $phpExePaht . ' recordShow.php';

    //$cmd = $cmd . ' -c ' . $channel . ' -u ' . $url . ' -n ' . $videoName . ' -l ' . $lengthInS;
    $cmd = $cmd . ' -c ' . $channel . ' -u ' . $url . ' -n ' . $videoName . ' -l ' . 30;
    echo $cmd . "<br>";
echo "<br>" .shell_exec($cmd);
//    execInBackground($cmd);
    $count++;
    if ($count >0) {
        break;
    }
}
echo microtime(true) - $totalstart . "<br>";
echo "$count<br>"
?>




<?php

function execInBackground($cmd) {
    if (substr(php_uname(), 0, 7) == "Windows") {
        pclose(popen("start /B " . $cmd, "r"));
    } else {
        echo php_uname();
        exec($cmd . " > /dev/null &");
    }
}
?>

