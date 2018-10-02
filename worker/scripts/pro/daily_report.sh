#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")

curl http://localhost/follows-worker/worker/scripts/daily_report.php > /opt/lampp/htdocs/follows-worker/worker/log/daily_report-${date}.log
