<?php
$idstream = $_GET["id"];
//kill old worker
$existpid = shell_exec("pgrep -f 'ffmpeg.*$idstream'");
exec("kill $existpid");
//create new one
exec("/usr/bin/nohup /var/www/html/yt-dlp_linux https://www.youtube.com/watch?v=$idstream -o - | ffmpeg -re -i - -acodec amr_wb -ar 16000 -ac 1 -vcodec h263 -vb 70k -r 15 -vf scale=176:144 -f rtsp rtsp://127.0.0.1:80/$idstream >/tmp/yt_dlpdebug.txt 2>&1 &");
sleep(15);
header("Location: rtsp://tv.tg-gw.com:80/$idstream");
echo "<a href=rtsp://tv.tg-gw.com:80/$idstream>Смотреть</a>";
