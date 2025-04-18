<title>Stream</title>
<?php
set_time_limit(60);
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
exec("/usr/bin/nohup /var/www/html/yt-dlp_linux --cookies /var/www/cookies.txt https://www.youtube.com/watch?v=$idstream -o - | ffmpeg -threads 0 -re -i - -t 18000 -acodec amr_wb -ar 16000 -ac 1 -ab 24.4k -vcodec mpeg4 -vb 100k -r 15 -g 15 -vf scale=320:240 -bufsize 10240k -maxrate 128k -f rtsp rtsp://127.0.0.1/$idstream?pkt_size=1316 >/tmp/yt_dlpdebug.txt 2>&1 &", $output, $return_var);
if ($return_var !== 0) {
    error_log("Failed to start ffmpeg: " . implode("\n", $output));
}

// Check for stream availability
$streamReady = false;
for ($i = 0; $i < 20; $i++) {
    $checkstream = exec("ffprobe -show_streams -v quiet rtsp://127.0.0.1/$idstream");
    if (!empty($checkstream)) {
        $streamReady = true;
        break;
    }
    sleep(3);
}

if (!$streamReady) {
    echo "<h2>Stream not available after 60 second.</h2>";
    exit;
}

echo "<h2>Stream started!</h2>";
echo "<br><a href=rtsp://139.28.223.207/$idstream>Watch (link 1)</a> *554 port<br>";
echo "<a href=rtsp://139.28.223.207:8080/$idstream>Watch (link 2)</a> *8080 port, использовать, если не работает первая<br>";
echo "<a href=index.php><font color=blue><<< back</font></a>";
?>
