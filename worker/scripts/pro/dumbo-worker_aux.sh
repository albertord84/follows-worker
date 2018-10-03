#!/bin/sh

parent_path=$( cd "$(dirname "${BASH_SOURCE[0]}")" ; pwd -P )

cd "$parent_path"

date=$(date +%Y%m%d)

now=$(date +"%T")

curl http://localhost/follows-worker/worker/index_aux.php > /opt/lampp/htdocs/follows-worker/worker/log/dumbo-worker-${date}.log