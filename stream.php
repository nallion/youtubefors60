<?php
$idstream = $_GET["id"];
if (!preg_match("/^[a-zA-Z0-9_-]{11}$/", $idstream)) { echo 404; die(); }
//kill old worker
$existpid = shell_exec("pgrep -f 'ffmpeg.*$idstream'");
exec("kill $existpid");
//create new one
exec("/usr/bin/nohup /var/www/html/yt-dlp_linux https://www.youtube.com/watch?v=$idstream -o - | ffmpeg -re -i - -acodec amr_wb -ar 16000 -ac 1 -vcodec h263 -vb 70k -r 15 -vf scale=176:144 -f rtsp rtsp://127.0.0.1:8080/$idstream >/tmp/yt_dlpdebug.txt 2>&1 &");
sleep(20);
echo "<a href=rtsp://tv.tg-gw.com:554/$idstream>Смотреть (ссылка 1)</a> *554 порт<br>";
echo "<a href=rtsp://tv.tg-gw.com:443/$idstream>Смотреть (ссылка 2)</a> *443 порт, использовать, если не работает первая<br>";
echo "<a href=rtsp://tv.tg-gw.com:8080/$idstream>Смотреть (ссылка 3)</a> *8080 порт, использовать, если не работают предыдущие<br>";
echo "<a href=rtsp://tv.tg-gw.com:8554/$idstream>Смотреть (ссылка 4)</a> *8554 можно попробовать и этот";
