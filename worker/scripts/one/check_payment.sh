#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")

curl http://127.0.0.1:30080/follows-worker/src/index.php/payment/check_payment >> /home/dumbuo5/public_html/follows-worker/src/logs/check-payment-${date}.log
#wget -O /home/dumbuo5/public_html/follows-worker/src/logs/check-payment-${date}.log   http://dumbu.one/follows-worker/src/index.php/payment/check_payment