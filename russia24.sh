while true
do
ffmpeg -re -i https://gtrk-volga.ru/media/hr24/stream1.m3u8 -acodec amr_wb -ar 16000 -ac 1 -vcodec mpeg4 -vb 100k -r 15 -g 15 -vf scale=320:240 -bufsize 10240k -maxrate 128k -f rtsp rtsp://127.0.0.1/russia24?pkt_size=1316
sleep 5
done
