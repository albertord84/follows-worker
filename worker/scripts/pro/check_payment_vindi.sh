#!/bin/sh

date=$(date +%Y%m%d)

now=$(date +"%T")

curl http://localhost/follows-worker/src/index.php/payment/check_payment_vindi >> /opt/lampp/htdocs/follows-worker/worker/log/vindi/check-payment-vindi-${date}.log