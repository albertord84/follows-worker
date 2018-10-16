#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")

curl http://127.0.0.1:30080/follows-worker/worker/scripts/daily_dumbu_statistics.php >> /home/dumbuo5/public_html/follows-worker/worker/log/daily_dumbu_statistics-${date}.log
