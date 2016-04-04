#!/bin/bash

which git > /dev/null || {
  sudo apt-get update
  sudo apt-get -y install nginx
  sudo apt-get -y install tcl
  sudo apt-get -y install git
  sudo apt-get -y install build-essential
}

which redis-cli > /dev/null || {
  curl -O http://download.redis.io/releases/redis-3.0.7.tar.gz
  tar -zxvf redis-3.0.7.tar.gz
  cd redis-3.0.7/
  make
  make test
  sudo make install
  sudo mkdir /etc/redis/
  sudo mkdir /var/log/redis/
  sudo touch /var/log/redis/redis.log
  sudo mkdir /var/opt/redis/
  cd /etc/redis/
  sudo curl -O https://raw.githubusercontent.com/artsmia/data-in-data-out-mw2016/master/redis.conf
  sudo redis-server /etc/redis/redis.conf
  cd ~
}

[[ -f /usr/share/elasticsearch/bin/elasticsearch ]] || {
  sudo apt-get -y install openjdk-7-jre
  curl -O https://download.elasticsearch.org/elasticsearch/release/org/elasticsearch/distribution/deb/elasticsearch/2.3.0/elasticsearch-2.3.0.deb
  sudo dpkg -i elasticsearch-2.3.0.deb
  sudo /etc/init.d/elasticsearch start
  sudo update-rc.d elasticsearch defaults
  curl -X GET http://127.0.0.1:9200/
}

which jq > /dev/null || sudo apt-get -y install jq parallel

[[ -d collection ]] || git clone https://github.com/artsmia/collection
[[ -d collection-elasticsearch ]] || git clone --depth 1 https://github.com/artsmia/collection-elasticsearch

which php5-fpm > /dev/null || {
  sudo apt-get -y install php5-fpm php5-curl
  sudo cp default.nginx /etc/nginx/sites-enabled/
  sudo service php5-fpm restart
  sudo service nginx reload
}
