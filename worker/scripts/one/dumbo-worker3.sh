#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")
curl http://127.0.0.1:30080/follows/worker/scripts/index-do.php?id=3 >> /home/dumbuo5/public_html/follows/worker/log/dumbo-worker3-${date}.log
#wget -O /home/dumbuo5/public_html/follows/worker/log/dumbo-worker3-${date}.log   http://127.0.0.1:30080/follows/worker/scripts/index-do.php?id=3