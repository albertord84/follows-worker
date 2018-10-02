#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")

curl http://localhost/follows-worker/src/index.php/payment/check_payment >> /opt/lampp/htdocs/follows-worker/worker/log/check-payment-${date}.log