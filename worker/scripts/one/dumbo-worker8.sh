#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")

curl http://127.0.0.1:30080/follows-worker/worker/scripts/index-do.php?id=8 >> /home/dumbuo5/public_html/follows-worker/worker/log/dumbo-worker8-${date}.log
#wget -O /home/dumbuo5/public_html/follows-worker/worker/log/dumbo-worker8-${date}.log   http://127.0.0.1:30080/follows-worker/worker/scripts/index-do.php?id=8