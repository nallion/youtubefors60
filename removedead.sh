#!/bin/bash

# Get the list of pids from the php script, excluding lines with "rtspSession"
getpids=$(php /root/getviewers.php | grep -v rtspSession)

# Loop through each pid retrieved
for pid in $getpids; do
    # Get the actual process IDs of ffmpeg processes related to the PID
    pids=$(ps -ax | grep "$pid" | grep "ffmpeg -re" | awk '{print $1}')
    # Check if there are any PIDs found and kill them
    if [ -n "$pids" ]; then
        kill $pids
        echo "Killed ffmpeg processes with PIDs: $pids"
    else
        echo "No ffmpeg processes found for PID: $pid"
    fi
done
