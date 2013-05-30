#!C:\xampp\php\php.exe -q
<?php
//all imputs are required
set_time_limit(0);
$shortopts = 'c:'; //channel
$shortopts.='u:'; //streaming url
$shortopts.='n:'; //name of the final file
$shortopts.='l:'; //lenght of the video in seconds

$options = getopt($shortopts);
if (!is_array($options)) {
    echo"There was a problem reading in the options.\n\n";
    exit(1);
}

print_r($options);
echo PHP_EOL;

$dir = __DIR__ . '/../web/channels/' . $options['c'] . '/';
if (!is_dir($dir)) {
    mkdir($dir);
}
$name = $dir . date("YmdHis") . "mm" . substr((string) microtime(), 2, 8) . ".mkv";
$count = 0;
$startTime = microtime(true);
$timeLeft = $options['l'];
//initialize
$cmd = "ffmpeg -i " . $options['u'] . " -t " . $timeLeft . " -y -c copy -bsf:a aac_adtstoasc " . $name;

echo $cmd . PHP_EOL;

shell_exec($cmd);

$count++;
$names[] = $name;

while ((microtime(true) - $startTime) < $options['l']) {
    $name = $dir . date("YmdHis") . "mm" . substr((string) microtime(), 2, 8) . ".mkv";
    $currentTime = microtime(true);
    $timeLeft = ceil($options['l'] - ($currentTime - $startTime));
    $cmd = "ffmpeg -i " . $options['u'] . " -t " . $timeLeft . " -y -c copy -bsf:a aac_adtstoasc" . $name;
    echo $cmd . PHP_EOL;
    shell_exec($cmd);

    $names[] = $name;
    $count++;
 }
/*
foreach ($names as $name){
    $cmd="ffmpeg -i " . $name . " -i " . $name ." -map 0:1 -map 1:0 -vcodec h264 -acodec libvo_aacenc -bsf:a aac_adtstoasc ".$options['n'];
 }
*/
/*
 * if there is more than 1 file it should be concatenated,  

*/

foreach ($names as $name) {
    if (is_readable($name)) {
        //unlink($name);
        //echo 'deleted:' . $name . PHP_EOL;
    }
}
exit;
?>