#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")

curl http://localhost/follows-worker/worker/scripts/daily_dumbu_statistics.php > /opt/lampp/htdocs/follows-worker/worker/log/daily_dumbu_statistics-${date}.log
