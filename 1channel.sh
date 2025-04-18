#!/bin/bash
while true
do
ffmpeg -re -i https://edge1.1internet.tv/dash-live2/streams/1tv-dvr/1tvdash.mpd -map 0:v:3 -map 0:a:1 -acodec amr_wb -ar 16000 -ac 1 -vcodec mpeg4 -vb 100k -r 15 -g 15 -vf scale=320:240 -bufsize 10240k -maxrate 128k -f rtsp rtsp://127.0.0.1/1channel?pkt_size=1316
sleep 5
done
