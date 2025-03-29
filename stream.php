<title>Stream</title>
<?php
set_time_limit(90);
$idstream = $_GET["id"];
if (!preg_match("/^[a-zA-Z0-9_-]{11}$/", $idstream)) { 
    echo 404; 
    die(); 
}

// Kill old worker
$existpid = shell_exec("pgrep -f 'ffmpeg.*$idstream'");
if ($existpid) {
    exec("kill $existpid", $output, $return_var);
    if ($return_var !== 0) {
        error_log("Failed to kill old ffmpeg process: " . implode("\n", $output));
    }
}

// Create new one
exec("/usr/bin/nohup /var/www/html/yt-dlp_linux https://www.youtube.com/watch?v=$idstream -o - | ffmpeg -re -i - -t 18000 -acodec amr_wb -ar 16000 -ac 1 -ab 24k -vcodec mpeg4 -vb 104k -r 15 -vf scale=320:240 -f rtsp rtsp://127.0.0.1:8080/$idstream?pkt_size=1316 >/tmp/yt_dlpdebug.txt 2>&1 &", $output, $return_var);
if ($return_var !== 0) {
    error_log("Failed to start ffmpeg: " . implode("\n", $output));
}

// Check for stream availability
$streamReady = false;
for ($i = 0; $i < 30; $i++) {
    $checkstream = exec("ffprobe -show_streams -v quiet rtsp://127.0.0.1:8080/$idstream");
    if (!empty($checkstream)) {
        $streamReady = true;
        break;
    }
    sleep(3);
}

if (!$streamReady) {
    echo "<h2>Stream not available after 90 second.</h2>";
    exit;
}

echo "<h2>Stream ready!</h2>";
echo "<br><a href=rtsp://tv.tg-gw.com:554/$idstream>Watch (link 1)</a> *554 порт<br>";
echo "<a href=rtsp://tv.tg-gw.com:443/$idstream>Watch (link 2)</a> *443 порт, использовать, если не работает первая<br>";
echo "<a href=rtsp://tv.tg-gw.com:8080/$idstream>Watch (link 3)</a> *8080 порт, использовать, если не работают предыдущие<br>";
echo "<a href=rtsp://tv.tg-gw.com:8554/$idstream>Watch (link 4)</a> *8554 можно попробовать и этот<br>";
echo "<a href=index.php><font color=blue><<< back</font></a>";
?>
