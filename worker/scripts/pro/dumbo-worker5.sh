#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")

curl http://localhost/follows-worker/worker/scripts/index-do.php?id=5 >> /opt/lampp/htdocs/follows-worker/worker/log/dumbo-worker5-${date}.log